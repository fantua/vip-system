<?php 
define('PAGE', 'edittariff');
require 'includes/include.php';

$id = (int)$_GET['id'];

if(!empty($_POST['submit'])){
    Tariffs::editTariff((int)$_POST['id']);
}

if(!empty($id)){
    $tariff = Tariffs::showEditTariff($id);
}else{
    Error::add('Ошибка, ID тарифа не указан!');
    header('Location: tariffs.php');
    die();
}

require 'templates/header.tpl';
?> 
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Редактировать тариф</span>
<form method="post">
<input type="hidden" name="id" value="<?=$id?>">
<table cellspacing='0' cellpadding='0' width="100%" class="cursive table table-striped table-bordered table-hover">
<?php
if(Error::isError()){
    $error = Error::view();
    echo '<tr><td colspan="2"><div class="alert alert-error"><center><font color="red">'.$error.'</font></center></div></td></tr>';
}
?>
    <tr>
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Название тарифа:</span></td>
        <td><input type='text' name='name' value='<?=$tariff['name']?>' placeHolder='Имя'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Срок действия:</span></td>
        <td><input type='text' placeHolder='Количество дней' name='term_limit' value='<?=$tariff['term_limit']?>'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Флаги доступа:</span></td>
        <td><input type='text' name='flags' value='<?=$tariff['group_flags']?>' placeHolder='Пример: abcei'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Иммунитет:</span></td>
        <td><input type='text' name='immunity' value='<?=$tariff['group_immunity']?>' placeHolder='Пример: 99'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">ID сервера:</span></td>
        <td><input type='text' name='server_id' value='<?=$tariff['server_id']?>' placeHolder='Задаёт: sm_vip_srvid'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMZ:</span></td>
        <td><input type='text' name='wmz' value='<?=$tariff['cost_wmz']?>' placeHolder='Доллары'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMU:</span></td>
        <td><input type='text' name='wmu' value='<?=$tariff['cost_wmu']?>' placeHolder='Гривны'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Стоимость в WMR:</span></td>
        <td><input type='text' name='wmr' value='<?=$tariff['cost_wmr']?>' placeHolder='Рубли'></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<?php
require 'templates/footer.tpl';
?>