<?php 

if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

class User{
    private static $hashSalt="fURWakljs3453";
    
    static 
    function start(){	
        if(PAGE != 'login' AND PAGE != 'result' AND !self::loginCheck()){	    
                header('Location: login.php');
                die(); 	    	    
        }
        if(PAGE == 'login' AND self::loginCheck()){
                header('Location: index.php');
                die(); 	    	    
        }
    }
    
    static
    function loginCheck(){
      if(!empty($_SESSION['admin'])){
            return true;
        }
    }
    
    static 
    function passwordHash($password){
        return sha1($password.self::$hashSalt);
    }
}

User::start();
?>