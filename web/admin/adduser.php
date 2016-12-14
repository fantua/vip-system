<?php 
define('PAGE', 'adduser');
require 'includes/include.php';

if(!empty($_POST['submit'])){
    Users::createUser();
}

$date = strtotime(date('d-m-Y'));
$time = date('d-m-Y', strtotime('+1 month', $date));

require 'templates/header.tpl';
?> 
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Добавить пользователя</span>
<form method="post">
<table cellspacing='0' cellpadding='0' width="100%" class="cursive table table-striped table-bordered table-hover">
<?php
if(Error::isError()){
    $error = Error::view();
    echo '<tr><td colspan="2"><div class="alert alert-error"><center><font color="red">'.$error.'</font></center></div></td></tr>';
}
?>
    <tr>
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Тариф:</span></td>
        <td><select name='tariff'>
<?php
Tariffs::showTariffsSelect();
?>
            </select></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">SteamID:</span></td>
        <td><input type='text' name='steamid'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Срок:</span></td>
        <td><input type='text' name='time' value="<?=$time?>"></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<?php
require 'templates/footer.tpl';
?>