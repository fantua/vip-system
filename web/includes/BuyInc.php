<?php 
class Buy{
    private $sql;
    public $steamid;
    private $tariffId;
    public $cost;
    private $ip;
    private $logId;
    public $tariff;
    public $payment;
            
    function __construct() {
        $this->validate();
        $this->logId = Logs::addLog($this->tariffId, $this->steamid, $this->cost, $this->ip);
        $this->payment = $this->merchantData();
    }
    
    private function validate(){
        $this->steamid = $_POST['steamid'];
        $this->tariffId = (int)$_POST['tariff'];
        $this->cost = $_POST['valuta'];
        $this->ip = ip2long($_SERVER['REMOTE_ADDR']);
        
        $this->sql = Sql::getInstance();
        
        if(!preg_match('#^STEAM_0:[01]:[0-9]{2,12}$#', $this->steamid)){
            Error::add('Данные введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }
        
        switch ($this->cost){
            case 'wmz': case 'wmu': case 'wmr': 
                break;
            default:
                Error::add('Данные введены неверно!');
                header('Location: '.PAGE.'.php');
                die();
        }
        $row = $this->sql->query("SELECT user_id FROM users WHERE tariff_id = '$this->tariffId' AND steamid = '$this->steamid'");
        if($row->num_rows){
            Error::add('Такой пользователь уже существует!');
            header('Location: '.PAGE.'.php');
            die();
        }
        $this->tariff = $this->sql->query("SELECT name, cost_$this->cost FROM tariffs WHERE tariff_id = '$this->tariffId' LIMIT 1");
        if(!$this->tariff->num_rows){
            Error::add('Данные введены неверно!');
            header('Location: '.PAGE.'.php');
            die();
        }else{
            $this->tariff = $this->tariff->fetch_assoc();
        }             
    }
    
    function merchantData(){
        $data['NO'] = $this->logId;
        $data['AMOUNT'] = $this->tariff['cost_'.$this->cost];
        
        $row = $this->sql->query("SELECT $this->cost, desc_prefix FROM settings LIMIT 1");
        if($row->num_rows){
            $row = $row->fetch_assoc();
        }else{
            die();
        }
        
        $data['PURSE'] = $row[$this->cost];
        $data['DESC'] = $row['desc_prefix'].' - '.$this->tariff['name'].' - ID '.$this->logId;
        
        return $data;
    }
    
}
?>
