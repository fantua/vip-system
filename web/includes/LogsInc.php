<?php 
class Logs{
    private $sql;
    private $page;
    private $limit = 15;
    private $startLimit;
    private $countLogs;
            
    function __construct() {
        $this->sql = Sql::getInstance();
        if(!empty($_GET['page'])){
            $this->page = (int)$_GET['page'];
        }else{
            $this->page = 0;
        }    
        $this->startLimit = $this->getLimit($this->page);
        $this->countLogs();
        if($this->startLimit>$this->countLogs){$this->startLimit=0;}
    }
    
    private function getLimit($page){
        $page = $page - 1;
        if($page > 0){
            return $page * $this->limit;
        }else{
            return 0;
        }
    }
    
    private function countLogs(){
        $logs = $this->sql->query("SELECT log_id FROM pay_logs WHERE status = 1");
        $this->countLogs = $logs->num_rows;
        if(empty($this->countLogs)){$this->countLogs=0;}
    }    
    
    public function showLogs(){
        if($this->countLogs){
            $logs = $this->sql->query("SELECT 
                                        log_id,
                                        tariff_id,
                                        steamid,
                                        type,
                                        trans_id,
                                        timestamp
                                            FROM pay_logs
                                                WHERE status = 1
                                                    ORDER BY log_id 
                                                        DESC
                                                            LIMIT ".$this->startLimit.",".$this->limit."");
            if($logs->num_rows){
                while ($log = $logs->fetch_assoc()){  
                    $log['timestamp'] = date('d-m-Y H:i', $log['timestamp']);
                    echo '<tr>    
                    <td>'.$log['log_id'].'</td>
                    <td>'.$log['tariff_id'].'</td>
                    <td>'.$log['type'].'</td>
                    <td>'.$log['steamid'].'</td>
                    <td>'.$log['trans_id'].'</td>
                    <td>'.$log['timestamp'].'</td>
                    </tr>';
                }
            }else{
                echo '<tr><td colspan="6" align="center"><div class="alert alert-block">Логов нет!</div></td></tr>';
            }
        }else{
            echo '<tr><td colspan="6" align="center"><div class="alert alert-block">Логов нет!</div></td></tr>';
        }
    }
    
    public function showPageList(){
        for($n=1; $n<ceil($this->countLogs/$this->limit); $n++){
            $selected = ($n == $this->page)?'selected':'';
            echo '<option value="'.$n.'" '.$selected.'>'.$n.'</option>';
        }
    }
   
    static function addLog($tariff_id, $steamid, $type, $ip){
        $sql = Sql::getInstance();
        if(!$sql->query("INSERT INTO pay_logs VALUES 
                                            (NULL,
                                            '$tariff_id',
                                            '$steamid',
                                            '$type',
                                            0,
                                            '',
                                            '$ip',
                                            '".time()."')")){           
            die();
        }else{
            return $sql->insert_id;
        }
    }
    
    static function updateLog($id, $transId){
        $sql = Sql::getInstance();   
        if(!$sql->query("UPDATE pay_logs SET 
                                        status = 1,
                                        trans_id = '$transId'
                                            WHERE log_id = '$id'")){       
            die();
        }
    }
}
?>