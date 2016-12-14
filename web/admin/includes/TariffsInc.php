<?php 
class Tariffs{
    static function showTariffs(){
        $sql = Sql::getInstance();
        $tariffs = $sql->query("SELECT 
                                        tariff_id,
                                        server_id,
                                        name,
                                        cost_wmz,                                       
                                        cost_wmu,
                                        cost_wmr,
                                        group_flags,
                                        group_immunity,
                                        term_limit                                   
                                            FROM tariffs
                                                    ORDER BY tariff_id");
        if($tariffs->num_rows){          
            while ($tariff = $tariffs->fetch_assoc()){
                echo '<tr>                
                <td>'.$tariff['tariff_id'].'</td>
                <td>'.$tariff['name'].'</td>
                <td>'.$tariff['cost_wmz'].' WMZ | '.$tariff['cost_wmu'].' WMU | '.$tariff['cost_wmr'].' WMR</td>
                <td>'.$tariff['group_flags'].'</td>
                <td>'.$tariff['group_immunity'].'</td>
                <td>'.$tariff['server_id'].'</td>
                <td>'.$tariff['term_limit'].'</td>                                                                                                                                                                                                                                                               
                <td><a href="edittariff.php?id='.$tariff['tariff_id'].'">Редактировать</a> / <a href="?tariffdel='.$tariff['tariff_id'].'">Удалить</a></td>
                </tr>';
            }
        }else{
            echo '<tr><td colspan="8" align="center"><div class="alert alert-block">Тарифов нет!</div></td></tr>';
        }
    }
    
    static function showTariffsSelect($id=FALSE){
        $sql = Sql::getInstance();
        $tariffs = $sql->query("SELECT 
                                        tariff_id,
                                        name,
                                        cost_wmz,                                       
                                        cost_wmu,
                                        cost_wmr
                                            FROM tariffs
                                                    ORDER BY tariff_id");
        if($tariffs->num_rows){          
            while ($tariff = $tariffs->fetch_assoc()){
                if($id){
                    $selected = ($id==$tariff['tariff_id'])?'selected':'';
                }else{
                    $selected = '';
                }
                echo '<option '.$selected.' value="'.$tariff['tariff_id'].'" data-wmz="'.$tariff['cost_wmz'].'" data-wmu="'.$tariff['cost_wmu'].'" data-wmr="'.$tariff['cost_wmr'].'">'.$tariff['name'].'</option>';
            }
        }else{
            echo '<option>Тарифов нет!</option>';
            die();
        }
    }
    
    static function validTariffData($page){
        $data['name'] = $_POST['name'];
        $data['wmz'] = (int)$_POST['wmz'];
        $data['wmu'] = (int)$_POST['wmu'];
        $data['wmr'] = (int)$_POST['wmr'];
        $data['server_id'] = (int)$_POST['server_id'];
        $data['flags'] = $_POST['flags'];
        $data['immunity'] = (int)$_POST['immunity'];
        $data['term_limit'] = (int)$_POST['term_limit'];                                                                                          
        
        if(empty($data['name']) OR empty($data['flags']) OR !preg_match('/^[a-z]+$/i', $data['flags'])){
            Error::add('Данные введены неверно!');
            header('Location: '.$page);
            die();
        }
        
        return $data;
    }

    static function addTariff(){
        $tariff = self::validTariffData(PAGE.'.php');       
        $sql = Sql::getInstance();       
        if($sql->query("INSERT INTO tariffs VALUES 
                                            (NULL,
                                            '{$tariff['server_id']}',
                                            '".$sql->real_escape_string($tariff['name'])."', 
                                            '{$tariff['wmz']}',
                                            '{$tariff['wmu']}',
                                            '{$tariff['wmr']}',
                                            '{$tariff['flags']}',
                                            '{$tariff['immunity']}',
                                            '{$tariff['term_limit']}')")){                                                                                         
            Error::add('Тариф добавлен!');
            header('Location: tariffs.php');
            die();
        }else{
            Error::add('При добавлении тарифа возникла ошибка! <br/>'.$sql->error);
            header('Location: '.PAGE.'.php');
            die();
        }
    }
    
    static function deleteTariff($id){
        $sql = Sql::getInstance();
        $sql->query("DELETE FROM tariffs WHERE tariff_id = '$id'");
        Error::add('Тариф удален!');
        header('Location: '.PAGE.'.php');
        die();
    }
    
    static function showEditTariff ($id){
        $sql = Sql::getInstance();
        $data = $sql->query("SELECT server_id, name, cost_wmz, cost_wmu, cost_wmr, group_flags, group_immunity, term_limit FROM tariffs WHERE tariff_id = '$id' LIMIT 1"); 
        if($data->num_rows){
            return $data->fetch_assoc();
        }else{
            Error::add('Ошибка, тарифа с таким ID не существует!');
            header('Location: tariffs.php');
            die();
        }
    }
    
    static function editTariff($id){        
        $tariff = self::validTariffData('edittariff.php?id='.$id);       
        $sql = Sql::getInstance();        
        if($sql->query("UPDATE tariffs SET 
                                        server_id = '{$tariff['server_id']}',
                                        name = '".$sql->real_escape_string($tariff['name'])."',                                            
                                        cost_wmz = '{$tariff['wmz']}',
                                        cost_wmu = '{$tariff['wmu']}',
                                        cost_wmr = '{$tariff['wmr']}',
                                        group_flags = '{$tariff['flags']}',
                                        group_immunity = '{$tariff['immunity']}',
                                        term_limit = '{$tariff['term_limit']}'                                                       
                                            WHERE tariff_id = '$id'")){       
                Error::add('Тариф изменен!');
                header('Location: tariffs.php');
                die();
            }else{
                Error::add('При изменении тарифа возникла ошибка! <br/>'.$sql->error);
                header('Location: tariffs.php');
                die();
            }
    }
}
?>