<?php
if(!defined('PAGE')) die('<b>Access Denied</b>');

define('IN_SYSTEM', TRUE);

require('ConfigurationSys.php');
require(ROOT.BS.'bdConfig.php');
require(ROOT.BS.'includes'.BS.'SqlInc.php');
require('UserInc.php');
require(ROOT.BS.'includes'.BS.'ErrorInc.php');


switch (PAGE){
    case 'login':
        require('AuthorizationInc.php');
        break;
    case 'index':
        require('UsersInc.php');
        break;
    case 'adduser':
        require('UsersInc.php');
        require('TariffsInc.php');
        break;
    case 'tariffs':
        require('TariffsInc.php');
        break;
    case 'addtariff':
        require('TariffsInc.php');
        break;   
    case 'edittariff':
        require('TariffsInc.php');
        break;
    case 'edituser':
        require('UsersInc.php');
        require('TariffsInc.php');
        break;
    case 'paylogs':
        require(ROOT.BS.'includes'.BS.'LogsInc.php');
        break;
    case 'settings':
        require('SettingsInc.php');
        break;
    case 'about':
        require('SettingsInc.php');
        break;
    case 'result':
        require('BuyResultInc.php');
        require('UsersInc.php');
        require(ROOT.BS.'includes'.BS.'LogsInc.php');
        break;
}

?>