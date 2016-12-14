<?php 
define('PAGE', 'result');
require 'includes/include.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $result = new BuyResult();
}
?>
