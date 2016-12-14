<?php 
define('PAGE', 'tariffs');
require 'includes/include.php';

require 'templates/header.tpl';

if(!empty($_GET['tariffdel'])){
    Tariffs::deleteTariff((int)$_GET['tariffdel']);
}
if(Error::isError()){
    $error = Error::view();
    echo '<div class="alert alert-error"><center><font color="red">'.$error.'</font></center></div>';
}
?>
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Тарифы</span><span style="float: right"><a href="addtariff.php"><button class="btn btn-primary" type="button">  Добавить тариф   </button></a></span>
        <table cellspacing='0' cellpadding='0' width="100%" class="table table-striped table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Стоимость</th>
                <th>Флаги доступа</th>
                <th>Иммунитет</th>
                <th>ID сервера</th>
                <th>Срок действия</th> 
                <th>*</th>
            </tr>
<?php
Tariffs::showTariffs();
?>
            
            
        </table>
<?php
require 'templates/footer.tpl';
?>