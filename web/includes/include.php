<?php 
if(!defined('PAGE')) die('<b>Access Denied</b>');

define('IN_SYSTEM', TRUE);

require('ConfigurationSys.php');
require(ROOT.BS.'bdConfig.php');
require('SqlInc.php');
require('ErrorInc.php');

switch (PAGE){
    case 'index':
        require('FunctionsInc.php');
        require('BuyInc.php');
        require('LogsInc.php');
        break;
}


?>
