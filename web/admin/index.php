<?php 
define('PAGE', 'index');
require 'includes/include.php';

if(!empty($_GET['userdel'])){
    Users::deleteUser((int)$_GET['userdel']);
}

require 'templates/header.tpl';
if(Error::isError()){
    $error = Error::view();
    echo '<center><div class="alert alert-error">'.$error.'</div></center>';
}
?>
<span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Пользователи</span><span style="float: right"><a href="adduser.php"><button class="btn btn-primary" type="button"> Добавить пользователя </button></a></span>
        <table cellspacing='0' cellpadding='0' width="100%" class="table table-striped table-bordered table-hover">
            <tr>
                <th>ID</th>
                <th>SteamID</th>
                <th>Срок</th>
                <th>Тариф</th>
                <th>*</th>
            </tr>
<?php
Users::showUsers();
?>
            
            
        </table>
<?php
require 'templates/footer.tpl';
?>