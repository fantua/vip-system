<?php 
define('PAGE', 'settings');
require 'includes/include.php';

$settings = Settings::showSettings();
$url = Settings::showUrl();

if(!empty($_POST['submit'])){
    Settings::editSettings();
}

if(!empty($_POST['submitpass'])){
    Settings::changePassword();
}

require 'templates/header.tpl';
?> 
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Настройки системы</span>
<form method="post">
<table cellspacing='0' cellpadding='0' width="100%" class="cursive table table-striped table-bordered table-hover">
<?php
if(Error::isError()){
    $error = Error::view();
    echo '<tr><td colspan="2"><div class="alert alert-error"><center><font color="red">'.$error.'</font></center></div></td></tr>';
}
?>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">Кошелек WMZ:</span></td>
        <td><input type='text' name='wmz' value='<?=$settings['wmz']?>'></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">Кошелек WMU:</span></td>
        <td><input type='text' name='wmu' value='<?=$settings['wmu']?>'></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">Кошелек WMR:</span></td>
        <td><input type='text' name='wmr' value='<?=$settings['wmr']?>'></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">Префикс назначения платежа:</span></td>
        <td><input type='text' name='desc_prefix' value='<?=$settings['desc_prefix']?>'></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">WebMoney Secret Key:</span></td>
        <td><input type='text' name='secret_key' value='<?=$settings['secret_key']?>'></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">WebMoney Result Url (Метод POST):</span></td>
        <td><?=$url['result'];?></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">WebMoney Success Url (Метод Link):</span></td>
        <td><?=$url['success'];?></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">WebMoney Fail Url (Метод Link):</span></td>
        <td><?=$url['fail'];?></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-xslarge uneditable-input" id="prependedInput" size="16" type="text">Метод формирования контрольной подписи:</span></td>
        <td><span class="input-small uneditable-input" id="prependedInput" size="16" type="text">MD5</span></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<br /> 
<span class="input-xlarge uneditable-input" id="prependedInput" size="16" type="text">Изменить пароль администратора</span>
<form method="post">
<table cellspacing='0' cellpadding='0' width="100%" class='cursive'>
    <tr>
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Новый пароль:</span></td>
        <td><input type='password' name='password' value='' placeholder="Password"></td>
    </tr>
    <tr>
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Повторите пароль:</span></td>
        <td><input type='password' name='password2' value='' placeholder="Confirm password"></td>
    </tr> 
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submitpass" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<?php
require 'templates/footer.tpl';
?>