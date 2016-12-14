<?php 
if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

class Error{
    static 
    function isError(){
        if(!empty($_SESSION['error'])){return true;}
    }
    
    static 
    function add($text){       
        $_SESSION['error'] = $text;      
    }
    
    static 
    function view(){       
        $error = $_SESSION['error'];
        unset($_SESSION['error']);
        
        return $error;
    }
}
?>
