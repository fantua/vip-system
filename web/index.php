<?php 
define('PAGE', 'index');
require 'includes/include.php';
require 'templates/header.tpl';

if(empty($_POST['submit'])){
?>
<form method="post">
<table cellspacing='0' cellpadding='0' width="100%" class="table table-bordered table-hover table-condensed">
<?php
if(Error::isError()){
    $error = Error::view();
    echo '<tr><td colspan="2" class="alert alert-error"><center><font color="red">'.$error.'</font></center></td></tr>';
}
?>
    <tr>
        <td><span class="input-medium uneditable-input">Ваш SteamID:</span></td>
        <td><input type='text' name='steamid' placeholder="Enter your SteamID here" class="span3"></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input">Выберите тариф:</span></td>
        <td>
            <select class="span3" name="tariff" id="tariff" OnChange="tariffChange()">
<?php
Functions::showTariffsSelect();
?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td><span class="input-medium uneditable-input">Выберите валюту:</span></td>
        <td>
            <select class="span3" name="valuta" id="valuta" OnChange="tariffChange()">
                <option value="wmz">Доллары (WebMoney WMZ)</option>
                <option value="wmu">Гривны (WebMoney WMU)</option>
                <option value="wmr">Рубли (WebMoney WMR)</option>            
            </select>
        </td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input">Стоимость:</span></td>
        <td>
            <div id="cost" class="input-small uneditable-input"></div>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><div class="form-signin"><button type="submit" name="submit" value="Заказать" class="btn btn-large btn-primary btn-block">Заказать</button></div></td>
    </tr>
</table>
</form>

<script type="text/javascript">
function tariffChange()
{
	var dtype="data-"+document.getElementById("valuta").value;
    document.getElementById("cost").innerHTML = document.getElementById("tariff").options[document.getElementById("tariff").selectedIndex].getAttribute(dtype) + " " + document.getElementById("valuta").value.toUpperCase();
}
</script>
<?php
}else{  
    $buy = new Buy();
?>
<table cellspacing='0' cellpadding='0' width="100%" class="table table-bordered table-hover">
    <tr>
        <td><span class="input-medium uneditable-input">Ваш SteamID:</span></td>
        <td><span class="input-large uneditable-input"><?=$buy->steamid;?></span></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input">Тариф:</span></td>
        <td><span class="input-large uneditable-input"><?=$buy->tariff['name'];?></span></td>
    </tr>
    <tr>
        <td><span class="input-medium uneditable-input">Стоимость:</span></td>
        <td><span class="input-large uneditable-input"><?=$buy->tariff['cost_'.$buy->cost].' '.$buy->cost;?></span></td>
    </tr>
    <tr>
        <td colspan="2" align="center">          
            <form method="POST" accept-charset="windows-1251" action="https://merchant.webmoney.ru/lmi/payment.asp">  
                <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$buy->payment['AMOUNT']?>">
                <input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$buy->payment['DESC']?>">
                <input type="hidden" name="LMI_PAYMENT_NO" value="<?=$buy->payment['NO']?>">
                <input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$buy->payment['PURSE']?>">
                <input type="hidden" name="LMI_SIM_MODE" value="0">
                <div class="form-signin"><input name="submit" type="submit" value="Оплатить" class="btn btn-large btn-primary btn-block">
            </form> 
        </td>
    </tr>
</table>
<?php
}
require 'templates/footer.tpl';
?>