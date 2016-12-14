<?php 
if(!defined('IN_SYSTEM')) die('<b>Access Denied</b>');

class Functions{
    static function showTariffsSelect(){
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
                echo '<option value="'.$tariff['tariff_id'].'" data-wmz="'.$tariff['cost_wmz'].'" data-wmu="'.$tariff['cost_wmu'].'" data-wmr="'.$tariff['cost_wmr'].'">'.$tariff['name'].'</option>';
            }
        }else{
            echo '<option>Тарифов нет!</option>';
            die();
        }
    }
    
}
?>
