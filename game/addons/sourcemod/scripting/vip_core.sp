//Based on standard SM plugin SQL Admins (Threaded)

#pragma semicolon 1
#include <sourcemod>

public Plugin:myinfo = 
{
	name = "VIP fetch",
	author = "KorDen",
	description = "Reads VIP/admins from database",
	version = "1.0",
	url = "dev.sky-play.ru"
};

/**
 * Notes:
 *
 * 1) All queries in here are high priority.  This is because the admin stuff 
 *    is very important.  Do not take this to mean that in your script, 
 *    everything should be high priority.
 *
 * 2) All callbacks are locked with "sequence numbers."  This is to make sure 
 *    that multiple calls to sm_reloadadmins and the like do not make us 
 *    store the results from two or more callbacks accidentally.  Instead, we 
 *    check the sequence number in each callback with the current "allowed" 
 *    sequence number, and if it doesn't match, the callback is cancelled.
 *
 * 3) Sequence numbers for groups and overrides are not cleared unless there 
 *    was a 100% success in the fetch.  This is so we can potentially implement 
 *    connection retries in the future.
 *
 * 4) Sequence numbers for the user cache are ignored except for being 
 *    non-zero, which means players in-game should be re-checked for admin 
 *    powers.
 */

new Handle:hDatabase = INVALID_HANDLE;			/** Database connection */
new g_sequence = 0;								/** Global unique sequence number */
new ConnectLock = 0;							/** Connect sequence number */
new RebuildCachePart[3] = {0};					/** Cache part sequence numbers */
new PlayerSeq[MAXPLAYERS+1];					/** Player-specific sequence numbers */
new bool:PlayerAuth[MAXPLAYERS+1];				/** Whether a player has been "pre-authed" */
new g_sId;


public OnPluginStart()
{
	new Handle:cvar = CreateConVar("sm_vip_srvid", "0", "Server ID for VIP system");
	HookConVarChange(cvar,Cvar_sId);
	g_sId=GetConVarInt(cvar);
	DumpAdminCache(AdminCache_Groups, true);
	CloseHandle(cvar);
}
public Cvar_sId(Handle:cvar, const String:oldvalue[], const String:newvalue[])
{
	g_sId=GetConVarInt(cvar);
	DumpAdminCache(AdminCache_Groups, true);
}

public OnMapEnd()
{
	// Clean up on map end just so we can start a fresh connection when we need it later
	if (hDatabase != INVALID_HANDLE)
	{
		CloseHandle(hDatabase);
		hDatabase = INVALID_HANDLE;
	}
}

public bool:OnClientConnect(client, String:rejectmsg[], maxlen)
{
	PlayerSeq[client] = 0;
	PlayerAuth[client] = false;
	return true;
}

public OnClientDisconnect(client)
{
	PlayerSeq[client] = 0;
	PlayerAuth[client] = false;
}

public OnDatabaseConnect(Handle:owner, Handle:hndl, const String:error[], any:data)
{
	// If this happens to be an old connection request, ignore it.
	if (data != ConnectLock || hDatabase != INVALID_HANDLE)
	{
		PrintToServer("[VIP] Erros in handles...");
		if (hndl != INVALID_HANDLE)
		{
			CloseHandle(hndl);
		}
		return;
	}
	
	ConnectLock = 0;
	hDatabase = hndl;
	
	/**
	 * See if the connection is valid.  If not, don't un-mark the caches
	 * as needing rebuilding, in case the next connection request works.
	 */
	if (hDatabase == INVALID_HANDLE)
	{
		LogError("Failed to connect to database: %s", error);
		return;
	}
	
	/**
	 * See if we need to get any of the cache stuff now.
	 */
	PrintToServer("[VIP] Successfully connected to DB");
	new sequence;
	if ((sequence = RebuildCachePart[_:AdminCache_Groups]) != 0)
	{
		FetchGroups(hDatabase, sequence);
	}
	if ((sequence = RebuildCachePart[_:AdminCache_Admins]) != 0)
	{
		FetchUsersWeCan(hDatabase);
	}
}

RequestDatabaseConnection()
{
	PrintToServer("[VIP] Connecting to the DB...");
	ConnectLock = ++g_sequence;
	if (SQL_CheckConfig("vipdb"))
	{
		SQL_TConnect(OnDatabaseConnect, "vipdb", ConnectLock);
	} else {
		SQL_TConnect(OnDatabaseConnect, "default", ConnectLock);
	}
}

public OnRebuildAdminCache(AdminCachePart:part)
{
	/**
	 * Mark this part of the cache as being rebuilt.  This is used by the 
	 * callback system to determine whether the results should still be 
	 * used.
	 */
	new sequence = ++g_sequence;
	RebuildCachePart[_:part] = sequence;
	
	/**
	 * If we don't have a database connection, we can't do any lookups just yet.
	 */
	if (!hDatabase)
	{
		/**
		 * Ask for a new connection if we need it.
		 */
		if (!ConnectLock)
		{
			RequestDatabaseConnection();
		}
		return;
	}
	
	if (part == AdminCache_Groups) {
		FetchGroups(hDatabase, sequence);
	} else if (part == AdminCache_Admins) {
		FetchUsersWeCan(hDatabase);
	}
}

public Action:OnClientPreAdminCheck(client)
{
	PlayerAuth[client] = true;
	
	/**
	 * Play nice with other plugins.  If there's no database, don't delay the 
	 * connection process.  Unfortunately, we can't attempt anything else and 
	 * we just have to hope either the database is waiting or someone will type 
	 * sm_reloadadmins.
	 */
	if (hDatabase == INVALID_HANDLE)
	{
		return Plugin_Continue;
	}
	
	/**
	 * Similarly, if the cache is in the process of being rebuilt, don't delay 
	 * the user's normal connection flow.  The database will soon auth the user 
	 * normally.
	 */
	if (RebuildCachePart[_:AdminCache_Admins] != 0)
	{
		return Plugin_Continue;
	}
	
	/*if (GetUserAdmin(client) != INVALID_ADMIN_ID)
	{
		return Plugin_Continue;
	}*/
	
	FetchUser(hDatabase, client);
	
	return Plugin_Handled;
}

public OnReceiveUser(Handle:owner, Handle:hndl, const String:error[], any:data)
{
	new Handle:pk = Handle:data;
	ResetPack(pk);
	
	new client = ReadPackCell(pk);
	
	/**
	 * Check if this is the latest result request.
	 */
	new sequence = ReadPackCell(pk);
	if (PlayerSeq[client] != sequence)
	{
		/* Discard everything, since we're out of sequence. */
		CloseHandle(pk);
		return;
	}
	
	/**
	 * If we need to use the results, make sure they succeeded.
	 */
	if (hndl == INVALID_HANDLE)
	{
		decl String:query[255];
		ReadPackString(pk, query, sizeof(query));
		LogError("SQL error receiving user: %s", error);
		LogError("Query dump: %s", query);
		RunAdminCacheChecks(client);
		NotifyPostAdminCheck(client);
		CloseHandle(pk);
		return;
	}
	
	new num_accounts = SQL_GetRowCount(hndl);
	if (num_accounts == 0)
	{
		RunAdminCacheChecks(client);
		NotifyPostAdminCheck(client);
		CloseHandle(pk);
		return;
	}
	
	decl String:identity[32], String:buffer[32];
	new AdminId:adm, time, GroupId:gid;
	
	while (SQL_FetchRow(hndl))
	{
		//SELECT user_id, tariff_id, steamid, timestamp FROM users WHERE steamid = '%s'
		
		SQL_FetchString(hndl, 2, identity, sizeof(identity));
		// For dynamic admins we clear anything already in the cache.
		adm = FindAdminByIdentity("steam", identity);
		Format(buffer,sizeof(buffer),"tariff_%d",SQL_FetchInt(hndl, 1));
		if((gid = FindAdmGroup(buffer)) != INVALID_GROUP_ID)
		{
			if (adm != INVALID_ADMIN_ID)
			{
				new i;
				PrintToServer("[VIP] Admin found, checking his groups...");
				for (i=0;i<GetAdminGroupCount(adm);i++)
				{
					if(GetAdminGroup(adm,i,buffer,sizeof(buffer))!=gid)
					{
						PrintToServer("[VIP] Admin - Found group %s, breaking..",buffer);
						break;
					}
					else
					{
						PrintToServer("[VIP] Admin - Found group %s, removing admin..",buffer);
						RemoveAdmin(adm);
					}
				}
			}
			time = SQL_FetchInt(hndl, 3);
			FormatTime(buffer, sizeof(buffer),"%c",time);
			PrintToServer("[VIP] Time: %d (%s) - DB",time,buffer);
			FormatTime(buffer, sizeof(buffer),"%c",GetTime());
			PrintToServer("[VIP] Time: %d (%s) - SRV",GetTime(),buffer);
			if(time>GetTime())
			{
				Format(buffer,sizeof(buffer),"VIP #%d", SQL_FetchInt(hndl, 0));
				adm = CreateAdmin(buffer);
				PrintToServer("[VIP] Adding admin %d: %s, sid %s...",adm, buffer,identity);
				if (!BindAdminIdentity(adm, "steam", identity))
				{
					LogError("Could not bind prefetched SQL admin (identity \"%s\")", identity);
					continue;
				}
				RunAdminCacheChecks(client);
				AdminInheritGroup(adm, gid);
			}
		}
	}
	NotifyPostAdminCheck(client);
	CloseHandle(pk);
}

FetchUser(Handle:db, client)
{
	PrintToServer("[VIP] Fetching user %d",client);
	decl String:steamid[32];
	steamid[0] = '\0';
	if (GetClientAuthString(client, steamid, sizeof(steamid)) && !StrEqual(steamid, "STEAM_ID_LAN"))
	{	
		decl String:query[512];
		ReplaceStringEx(steamid,sizeof(steamid),"STEAM_1","STEAM_0");
		Format(query, sizeof(query), "SELECT user_id, tariff_id, steamid, timestamp FROM users WHERE steamid = '%s' ", steamid);
		PlayerSeq[client] = ++g_sequence;
	
		new Handle:pk;
		pk = CreateDataPack();
		WritePackCell(pk, client);
		WritePackCell(pk, PlayerSeq[client]);
		WritePackString(pk, query);
		
		SQL_TQuery(db, OnReceiveUser, query, pk, DBPrio_High);	
	}
}

FetchUsersWeCan(Handle:db)
{
	for (new i=1; i<=MaxClients; i++)
		if (PlayerAuth[i] && GetUserAdmin(i) == INVALID_ADMIN_ID)
			FetchUser(db, i);
	
	// This round of updates is done.  Go in peace.
	RebuildCachePart[_:AdminCache_Admins] = 0;
}

public OnReceiveGroups(Handle:owner, Handle:hndl, const String:error[], any:data)
{
	PrintToServer("[VIP] Groups query received");
	new Handle:pk = Handle:data;
	ResetPack(pk);
	
	/**
	 * Check if this is the latest result request.
	 */
	new sequence = ReadPackCell(pk);
	if (RebuildCachePart[_:AdminCache_Groups] != sequence)
	{
		/* Discard everything, since we're out of sequence. */
		CloseHandle(pk);
		return;
	}
	
	/**
	 * If we need to use the results, make sure they succeeded.
	 */
	if (hndl == INVALID_HANDLE)
	{
		decl String:query[255];
		ReadPackString(pk, query, sizeof(query));
		LogError("SQL error receiving groups: %s", error);
		LogError("Query dump: %s", query);
		CloseHandle(pk);
		return;
	}
	
	/**
	 * Now start fetching groups.
	 */
	decl String:flags[32];
	decl String:name[128];
	new immunity, tariffid;
	while (SQL_FetchRow(hndl))
	{
		tariffid = SQL_FetchInt(hndl, 0);
		SQL_FetchString(hndl, 1, flags, sizeof(flags));
		immunity = SQL_FetchInt(hndl, 2);
		Format(name,sizeof(name),"tariff_%d",tariffid);
		PrintToServer("[VIP] Adding admin group %s (%d:%s)", name, immunity, flags);
		
		/* Find or create the group */
		new GroupId:gid;
		if ((gid = FindAdmGroup(name)) == INVALID_GROUP_ID)
			gid = CreateAdmGroup(name);

		/* Add flags from the database to the group */
		new num_flag_chars = strlen(flags);
		decl AdminFlag:flag;
		for (new i=0; i<num_flag_chars; i++)
			if (FindFlagByChar(flags[i], flag))
				SetAdmGroupAddFlag(gid, flag, true);
		
		SetAdmGroupImmunityLevel(gid, immunity);
	}
}

FetchGroups(Handle:db, sequence)
{
	PrintToServer("[VIP] Fetching groups...");
	decl String:query[255];
	new Handle:pk;
	
	Format(query, sizeof(query), "SELECT tariff_id, group_flags, group_immunity FROM tariffs WHERE server_id = %d",g_sId);

	pk = CreateDataPack();
	WritePackCell(pk, sequence);
	WritePackString(pk, query);
	
	SQL_TQuery(db, OnReceiveGroups, query, pk, DBPrio_High);
}