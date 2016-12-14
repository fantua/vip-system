<?php 
class Users{   
    static function showUsers(){
        $sql = Sql::getInstance();
        $users = $sql->query("SELECT 
                                    users.user_id,
                                    users.tariff_id,
                                    users.steamid,                                       
                                    users.timestamp,
                                    tariffs.name
                                        FROM users
                                            INNER JOIN tariffs ON (tariffs.tariff_id=users.tariff_id)
                                                ORDER BY users.user_id");
        if($users->num_rows){
            while ($user = $users->fetch_assoc()){  
                $user['timestamp'] = date('d-m-Y', $user['timestamp']);
                echo '<tr>    
                <td>'.$user['user_id'].'</td>
                <td>'.$user['steamid'].'</td>
                <td>'.$user['timestamp'].'</td>
                <td>'.$user['name'].'</td>
                <td><a href="edituser.php?id='.$user['user_id'].'">Редактировать</a> / <a href="?userdel='.$user['user_id'].'">Удалить</a></td>
                </tr>';
            }
        }else{
            echo '<tr><td colspan="5" align="center"><div class="alert alert-block">Пользователей нет!</div></td></tr>';
        }
    }
    
    static function showEditUser($id){
        $sql = Sql::getInstance();
        $data = $sql->query("SELECT user_id, tariff_id, steamid, timestamp FROM users WHERE user_id = '$id' LIMIT 1");
        if($data->num_rows){
            return $data->fetch_assoc();
        }else{
            Error::add('Ошибка, пользователя с таким ID не существует!');
            header('Location: users.php');
            die();
        }
    }
    
    static function deleteUser($id){
        $sql = Sql::getInstance();
        $sql->query("DELETE FROM users WHERE user_id = '$id'");
        Error::add('Пользователь удален!');
        header('Location: '.PAGE.'.php');
        die();
    }
    
    static function validUserData($page){
        $data['steamid'] = $_POST['steamid'];
        $data['time'] = $_POST['time'];
        $data['tariff'] = (int)$_POST['tariff'];
        
        if(!preg_match('#^STEAM_0:[01]:[0-9]{2,12}$#', $data['steamid']) OR
           !preg_match('#^[0-9]{2}-[0-9]{2}-[0-9]{4}$#', $data['time'])){
            Error::add('Данные введены неверно!');
            header('Location: '.$page);
            die();
        }
        
        $data['time'] = strtotime($data['time']);
        
        return $data;
    }

    static function createUser(){
        $user = self::validUserData(PAGE.'.php');
        
        $sql = Sql::getInstance();
        
        if($sql->query("INSERT INTO users VALUES 
                                            (NULL,
                                            '{$user['tariff']}',
                                            '{$user['steamid']}',
                                            '{$user['time']}')")){       
                Error::add('Пользователь добавлен!');
                header('Location: index.php');
                die();
            }else{
                Error::add('При добавлении пользователя возникла ошибка! <br/>'.$sql->error);
                header('Location: index.php');
                die();
            }
    }

    static function editUser($id){
        $user = self::validUserData(PAGE.'.php?id='.$id);
        
        $sql = Sql::getInstance();
        
        if($sql->query("UPDATE users SET 
                                        tariff_id = '{$user['tariff']}',
                                        steamid = '{$user['steamid']}',
                                        timestamp = '{$user['time']}'
                                            WHERE user_id = '$id'")){       
                Error::add('Пользователь изменен!');
                header('Location: index.php');
                die();
            }else{
                Error::add('При изменении пользователя возникла ошибка! <br/>'.$sql->error);
                header('Location: index.php');
                die();
            }
    }
 
    static function addUser($tariffId, $steamid){       
        $sql = Sql::getInstance();  
        $result = $sql->query("SELECT term_limit FROM tariffs WHERE tariff_id = '$tariffId'");  
        $row = $result->fetch_assoc();                                                                    
        $term_limit=$row["term_limit"];                                                                                                                                                                                   
        if(!$sql->query("INSERT INTO users VALUES 
                                            (NULL,
                                            '$tariffId',
                                            '$steamid',
                                            '".strtotime('+'.$term_limit.' day', time())."')")){                       
            die();
        }
    }
}

?>