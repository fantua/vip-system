CREATE  TABLE tariffs 
(
	tariff_id MEDIUMINT UNSIGNED NOT NULL auto_increment,
	server_id SMALLINT UNSIGNED NOT NULL,
	name VARCHAR(255) NOT NULL,
	cost_wmz SMALLINT UNSIGNED NOT NULL,
	cost_wmu SMALLINT UNSIGNED NOT NULL,
	cost_wmr SMALLINT UNSIGNED NOT NULL,
	group_flags VARCHAR(30) NOT NULL,
	group_immunity TINYINT UNSIGNED NOT NULL,
	term_limit SMALLINT UNSIGNED NOT NULL,
	CONSTRAINT pkTariffId PRIMARY KEY (tariff_id),
	INDEX ixServerId (server_id)
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE  TABLE users 
(
	user_id MEDIUMINT UNSIGNED NOT NULL auto_increment,
	tariff_id MEDIUMINT UNSIGNED NOT NULL,
	steamid VARCHAR(65) NOT NULL,
	timestamp INT UNSIGNED NOT NULL,
	CONSTRAINT pkUserId PRIMARY KEY (user_id),
	INDEX ixTariffId (tariff_id),
	INDEX ixSteamid (steamid),
	CONSTRAINT fkTariffsId FOREIGN KEY (tariff_id)
		REFERENCES tariffs (tariff_id)
			ON DELETE CASCADE
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE  TABLE pay_logs 
(
  log_id INT UNSIGNED NOT NULL auto_increment,
  tariff_id MEDIUMINT UNSIGNED NOT NULL,
  steamid VARCHAR(65) NOT NULL,
  type VARCHAR(3) NOT NULL,
  status BOOLEAN DEFAULT '0' NOT NULL,
  trans_id INT UNSIGNED NOT NULL,
  ip INT UNSIGNED NOT NULL,
  timestamp INT UNSIGNED NOT NULL,
  CONSTRAINT pkLogId PRIMARY KEY (log_id)
) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE  TABLE settings 
(
	wmz VARCHAR(13) NULL,
	wmu VARCHAR(13) NULL,
	wmr VARCHAR(13) NULL,
	secret_key VARCHAR(50) NULL,
	desc_prefix VARCHAR(255) NULL,
	password VARCHAR(40) NOT NULL,
	version FLOAT UNSIGNED NOT NULL
) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

INSERT INTO settings (password, version) VALUES ('2fd01682e78eabb59f188e1026479e91b0332741', 1.1);
