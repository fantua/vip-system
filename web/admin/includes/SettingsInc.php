<?php 
class Settings{
    static function showSettings(){
        $sql = Sql::getInstance();
        $settings = $sql->query("SELECT wmz, wmu, wmr, desc_prefix, secret_key FROM settings LIMIT 1");
        if($settings->num_rows){
            return $settings->fetch_assoc();
        }
    }
    
    static function editSettings(){
        if(!preg_match('#^Z[0-9]{12}$#', $_POST['wmz']) OR
           !preg_match('#^U[0-9]{12}$#', $_POST['wmu']) OR
           !preg_match('#^R[0-9]{12}$#', $_POST['wmr']) OR
           empty($_POST['desc_prefix']) OR
           empty($_POST['secret_key'])){
            Error::add('Данные введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }
        
        $sql = Sql::getInstance();
        
        if($sql->query("UPDATE settings SET 
                                        wmz = '{$_POST['wmz']}',
                                        wmr = '{$_POST['wmr']}',
                                        wmu = '{$_POST['wmu']}',
                                        desc_prefix = '".$sql->real_escape_string($_POST['desc_prefix'])."',
                                        secret_key = '".$sql->real_escape_string($_POST['secret_key'])."'")){       
                Error::add('Настройки изменены!');
                header('Location: '.PAGE.'.php');
                die();
            }else{
                Error::add('При изменении настроек возникла ошибка! <br/>'.$sql->error);
                header('Location: '.PAGE.'.php');
                die();
            }
    }
    
    static function changePassword(){
        if($_POST['password'] != $_POST['password2'] OR 
           strlen($_POST['password'])<6 OR 
           !preg_match('#^[a-z0-9._-]+$#i', $_POST['password'])){
            Error::add('Данные введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }
        
        $sql = Sql::getInstance();
        $password = User::passwordHash($_POST['password']);
        
        if($sql->query("UPDATE settings SET password = '$password'")){       
                Error::add('Настройки изменены!');
                header('Location: '.PAGE.'.php');
                die();
            }else{
                Error::add('При измении настроек возникла ошибка! <br/>'.$sql->error);
                header('Location: '.PAGE.'.php');
                die();
            }
    }
    
    static function showVersion(){
        $sql = Sql::getInstance();
        $result = $sql->query("SELECT version FROM settings LIMIT 1");
        if($result->num_rows){
            $result = $result->fetch_assoc();
            return $result['version'];
        }
    }
    
    static function showUrl(){        
        $dir = preg_replace('#/.[^/]*+$#','',$_SERVER['SCRIPT_NAME']);
        $dirRoot = preg_replace('#(/.[^/]*){2}+$#','',$_SERVER['SCRIPT_NAME']);
        $domen = 'http://'.$_SERVER['HTTP_HOST'];
        $url['result'] = $domen.$dir.'/result.php';
        $url['success'] = $domen.$dirRoot.'/success.php';
        $url['fail'] = $domen.$dirRoot.'/index.php';              
        return $url;
    }
}
?>
