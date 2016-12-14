<?php 
class BuyResult{
    private $payment;
    private $data;
    private $sql;
            
    function __construct() {
        $this->sql = Sql::getInstance();
        $this->merchantData();
        $this->validate();
        Users::addUser($this->data['tariff_id'], $this->data['steamid']);
        Logs::updateLog($this->data['log_id'], $_POST['LMI_SYS_TRANS_NO']);
    }
    
    private function validate(){
        $sig = $this->data[$this->data['type']].
               $this->data['cost_'.$this->data['type']].'.00'.
               $this->data['log_id'].
               $_POST['LMI_MODE'].
               $_POST['LMI_SYS_INVS_NO'].
               $_POST['LMI_SYS_TRANS_NO'].
               $_POST['LMI_SYS_TRANS_DATE'].
               $this->data['secret_key'].
               $_POST['LMI_PAYER_PURSE'].
               $_POST['LMI_PAYER_WM'];
        $sig = strtoupper(md5($sig));
        if($sig != $_POST['LMI_HASH']){
            die();
        }
        
    }
    
    private function merchantData(){
        $logId = (int)$_POST['LMI_PAYMENT_NO'];
        
        $log = $this->sql->query("SELECT log_id,
                                         tariff_id,
                                         steamid,
                                         type
                                            FROM pay_logs
                                                WHERE log_id = '$logId'
                                                    LIMIT 1");
        if($log->num_rows){
            $log = $log->fetch_assoc();
        }else{
            die();
        }
        
        $tariff = $this->sql->query("SELECT cost_{$log['type']}
                                                FROM tariffs
                                                    WHERE tariff_id = '{$log['tariff_id']}'
                                                        LIMIT 1");
        if($tariff->num_rows){
            $tariff = $tariff->fetch_assoc();
        }else{
            die();
        }
        
        $settings = $this->sql->query("SELECT {$log['type']},
                                              secret_key
                                                FROM settings
                                                    LIMIT 1");
        if($settings->num_rows){
            $settings = $settings->fetch_assoc();
        }else{
            die();
        }
        
        $this->data = array_merge($log, $tariff, $settings);      
    }
    
}
?>
