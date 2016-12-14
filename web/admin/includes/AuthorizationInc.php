<?php 
if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

class Authorization{
    private $login;
    private $password;
    private $sql;
    
    function __construct() {
        $this->login = trim($_POST['login']);
        $this->password = trim($_POST['password']);
        
        $this->validate();
        
        $this->sql = Sql::getInstance();
        $this->testLoginDb();
        
        $this->loginIn();
        
    }
    
    function validate(){
        if($this->login != 'admin'){
            Error::add('Логин или пароль введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }
        if(strlen($this->password)<6 OR !preg_match('#^[a-z0-9._-]+$#i', $this->password)){
            Error::add('Логин или пароль введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }
    }
    function testLoginDb(){
        $this->password = User::passwordHash($this->password);
        $result = $this->sql->query("SELECT password FROM settings WHERE password = '$this->password' LIMIT 1");
        if(!$result->num_rows){
            Error::add('Логин или пароль введены неверно! ');
            header('Location: '.PAGE.'.php');
            die();
        }
    }
    
    function loginIn(){
        $_SESSION['admin'] = TRUE;
        header('Location: index.php');
        die();
    }
}
?>
