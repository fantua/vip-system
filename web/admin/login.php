<?php
define('PAGE','login');
require 'includes/include.php';

if(!empty($_POST['submit'])){
    $authorization = new Authorization();
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="templates/css/bootstrap-glyphicons.css" />
<link rel="stylesheet" href="templates/css/bootstrap-responsive.css" />
<link rel="stylesheet" href="templates/css/bootstrap.css" />
<link rel="stylesheet" href="templates/css/signin.css" />
<title>VIP System</title>
</head>
<body>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="templates/js/bootstrap.min.js"></script>   

<div class="container">
<form method="post" class="form-signin">

<?php
if(Error::isError()){
    $error = Error::view();
    echo '<div class="alert alert-error"><center>'.$error.'</font></center></div>';
}
?>
            <h2 class="form-signin-heading" align="center">Please log in</h2>
            <input name="login" type="text" class="form-control input-xlarge btn-block" placeholder="Ваш логин" autofocus>
            <input type="password" name="password" class="form-control input-xlarge btn-block" placeholder="Ваш пароль">
            <input type="submit" name="submit" value="Войти" class="btn btn-large btn-primary btn-block">

</form>
</div>
</body>
</html>