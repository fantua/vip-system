<?php 
define('PAGE', 'about');
require 'includes/include.php';
require 'templates/header.tpl';
?> 
<span class="input-xlarge uneditable-input" id="prependedInput" size="16" type="text">О системе.</span>
<table cellspacing='0' cellpadding='0' width="100%" class="cursive table table-striped table-bordered table-hover">
   
    <tr>
        <td width='30%'><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Версия системы:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-cog"></i></span>
      <span class="input-large uneditable-input" id="prependedInput" size="16" type="text"><?=Settings::showVersion();?></span>
    </div>
  </div>
</div>
</td>
    </tr>
    <tr>
        <td><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Разработчик:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
      <span class="input-large uneditable-input" id="prependedInput" size="16" type="text"><a href="http://hlmod.ru/forum/member.php?u=20951">Vaio</a></span>
    </div>
  </div>
</div></td>
    </tr>
    <tr>
        <td><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">ICQ:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
      <span class="input-large uneditable-input" id="prependedInput" size="16" type="text">618294270</span>
    </div>
  </div>
</div></td>
    </tr>
    <tr>
        <td><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Skype:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>
      <span class="input-large uneditable-input" id="prependedInput" size="16" type="text">garbarua</span>
    </div>
  </div>
</div></td>
    </tr>	
    <tr>
        <td><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Обсуждение:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
      <span class="input-large uneditable-input" id="prependedInput" size="16" type="text"><a href="http://hlmod.ru/forum/showthread.php?p=123015">Здесь</a></span>
    </div>
  </div>
</div></td>
    </tr>
    <tr>
        <td><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Донат:</span></td>
        <td><div class="control-group"><div class="controls"><div class="input-prepend"><span class="add-on"><i class="icon-gift"></i></span>
     <span class="input-large uneditable-input" id="prependedInput" size="16" type="text">Z390006598151</span><br/>
     <span class="add-on"><i class="icon-gift"></i></span><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">U407370876450</span><br />
     <span class="add-on"><i class="icon-gift"></i></span><span class="input-large uneditable-input" id="prependedInput" size="16" type="text">R251381048204</span>
     </div></div></div>
        </td>
    </tr>

</table>
<?php
require 'templates/footer.tpl';
?>