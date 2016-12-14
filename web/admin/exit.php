<?php 
session_start();
$_SESSION=false;
session_destroy();
header('Location: login.php');
die();
?>
