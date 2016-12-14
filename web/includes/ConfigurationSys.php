<?php
if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

ini_set('display_errors',1);
error_reporting(E_ALL | E_STRICT);

session_start();

define('BS',DIRECTORY_SEPARATOR);
define('ROOT',realpath('.'.BS));


class Configurate{
    
}
?>