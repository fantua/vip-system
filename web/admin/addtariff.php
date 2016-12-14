<?php 
define('PAGE', 'addtariff');
require 'includes/include.php';

if(!empty($_POST['submit'])){
    Tariffs::addTariff();
}

require 'templates/header.tpl';
?> 
<span class="input-xlarge uneditable-input" id="prependedInput" size="16" type="text">Добавить тариф</span>
<form method="post">
<table cellspacing='0' cellpadding='0' width="100%" class="cursive table table-striped table-bordered table-hover">
<?php
if(Error::isError()){
    $error = Error::view();
    echo '<tr><td colspan="2"><div class="alert alert-error"><center><font color="red">'.$error.'</font></center></div></td></tr>';
}
?>
    <tr>
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Название тарифа:</span></td>
        <td><input type='text' name='name' placeHolder='Имя'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Срок действия:</span></td>
        <td><input type='text' name='term_limit' placeHolder='Количество дней'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Флаги доступа:</span></td>
        <td><input type='text' name='flags' placeHolder='Пример: abcei'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Иммунитет:</span></td>
        <td><input type='text' name='immunity' placeHolder='Пример: 99'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">ID сервера:</span></td>
        <td><input type='text' name='server_id' placeHolder='Задаёт: sm_vip_srvid'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMZ:</span></td>
        <td><input type='text' name='wmz' placeHolder='Доллары'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMU:</span></td>
        <td><input type='text' name='wmu' placeHolder='Гривны'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMR:</span></td>
        <td><input type='text' name='wmr' placeHolder='Рубли'></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<?php
require 'templates/footer.tpl';
?>