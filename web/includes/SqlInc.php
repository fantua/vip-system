<?php 
if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

class Sql extends mysqli{
    private $db_encoding = 'utf8';
    private static $instance;
 
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
 
    private function __construct(){
        parent::__construct(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if (mysqli_connect_error()) {
            die('Ошибка подключения к MySQL');
        }
        parent::set_charset($this->db_encoding);
    }
 
    public 
    function __destruct(){	
        return parent::close();
    }
}
?>
