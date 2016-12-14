<?php 
define('PAGE', 'edituser');
require 'includes/include.php';

$id = (int)$_GET['id'];

if(!empty($_POST['submit'])){
    Users::editUser((int)$_POST['id']);
}

if(!empty($id)){
    $user = Users::showEditUser($id);
    $date = date('d-m-Y', $user['timestamp']);
}else{
    Error::add('Ошибка, ID пользователя не указан!');
    header('Location: index.php');
    die();
}

require 'templates/header.tpl';
?> 
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Редактировать пользователя</span>
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
        <td width='30%'><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">SteamID:</span></td>
        <td><input type='text' name='steamid' value='<?=$user['steamid']?>'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Срок:</span></td>
        <td><input type='text' name='time' value='<?=$date?>'></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input" id="prependedInput" size="16" type="text">Тариф:</span></td>
        <td>
            <select name="tariff">
<?php 
Tariffs::showTariffsSelect($user['tariff_id']);
?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><input type="submit" name="submit" value="Сохранить" class="btn btn-primary"></td>
    </tr>
</table>
</form>
<?php
require 'templates/footer.tpl';
?>