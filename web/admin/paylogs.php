<?php 
define('PAGE', 'paylogs');
require 'includes/include.php';
require 'templates/header.tpl';

$obj = new Logs();
?>
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Логи оплат</span>
        <table cellspacing='0' cellpadding='0' width="100%" class="table table-striped table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Тариф</th>
                <th>Валюта</th>
                <th>SteamID</th>
                <th>Номер платежа WM</th>
                <th>Дата</th>
            </tr>
<?php
$obj->showLogs();
?>                      
        </table>
<form method="get" action="paylogs.php" align="right">
    <span class="input-small uneditable-input" id="prependedInput" size="16" type="text">Страница</span> 
    <select name="page" class="select" onchange="submit();">
<?php 
$obj->showPageList(); 
?>
    </select>
</form>   
<?php
require 'templates/footer.tpl';
?>