<?php

defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');

JHtml::_('behavior.tooltip');

jimport ('joomla.plugin.helper');
jimport('joomla.html.pane');
  if(!JPluginHelper::isEnabled('system','socialloginandsocialshare')) :
    JError::raiseNotice ('sociallogin_plugin', JText::_ ('COM_SOCIALLOGIN_PLUGIN_ERROR')); 
   endif;
   if(!JPluginHelper::isEnabled('content','socialshare')) :
    JError::raiseNotice ('sociallogin_plugin', JText::_ ('COM_SOCIALSHARE_PLUGIN_ERROR')); 
   endif;
  if(!JModuleHelper::isEnabled('mod_socialloginandsocialshare')) :
    JError::raiseNotice ('sociallogin_module', JText::_ ('MOD_SOCIALLOGIN_PLUGIN_ERROR')); 
   endif;  
  if(!isset($this->settings['apikey']) || $this->settings['apikey'] == "" || !isset($this->settings['apisecret']) || $this->settings['apisecret'] == "") :
   JError::raiseNotice ('sociallogin_plugin', JText::_ ('COM_SOCIALLOGIN_APIKEY_SECRET_NOTIFICATION'));
   endif;
?>
<script type="text/javascript">
window.onload=function(){

var shareProvider = $SS.Providers.More;
var counterProvider = $SC.Providers.All;
<?php $horizontal_rearrange = (isset($this->settings['horizontal_rearrange']) ? $this->settings['horizontal_rearrange'] : "");?>
<?php $vertical_rearrange = (isset($this->settings['vertical_rearrange']) ? $this->settings['vertical_rearrange'] : "");?>
<?php $horizontalcounter = (isset($this->settings['horizontalcounter']) ? $this->settings['horizontalcounter'] : "");?>
<?php $verticalcounter = (isset($this->settings['verticalcounter']) ? $this->settings['verticalcounter'] : "");?>
<?php if(empty($horizontalcounter)){
		$horizontalcounter = '["Facebook Like","Twitter Tweet","Google+ Share","LinkedIn Share"]';
	}
	else{
		$horizontalcounter = json_encode(unserialize($horizontalcounter));
	}
?>
<?php if(empty($verticalcounter)){
		$verticalcounter = '["Facebook Like","Twitter Tweet","Google+ Share","LinkedIn Share"];';
	}
	else{
		$verticalcounter = json_encode(unserialize($verticalcounter));
	}?>
<?php if(empty($horizontal_rearrange)){
		$horizontal_rearrange = '["facebook","twitter","pinterest","googleplus","linkedin"]';
	}
	else{
		$horizontal_rearrange = json_encode(unserialize($horizontal_rearrange));
	}
?>
<?php if(empty($vertical_rearrange)){
		$vertical_rearrange = '["facebook","twitter","pinterest","googleplus","linkedin"]';
	}
	else{
		$vertical_rearrange = json_encode(unserialize($vertical_rearrange));
	}
?>
var horshareChecked = <?php echo $horizontal_rearrange; ?>;
var vershareChecked = <?php echo $vertical_rearrange; ?>;
var horcounterChecked = <?php echo $horizontalcounter; ?>;
var vercounterChecked = <?php echo $verticalcounter; ?>;
var SSP = document.getElementById('sharehprovider');

for(var i = 0; i < shareProvider.length; i++)
{
	var horizontalSharingProvidersContainer = document.createElement('div');
	horizontalSharingProvidersContainer.setAttribute('class','loginRadiusProviders');
	var shareDiv = document.createElement('input');
	shareDiv.setAttribute('type','checkbox');
	shareDiv.setAttribute('id','horizontalsharingid'+shareProvider[i].toLowerCase());		
	shareDiv.setAttribute('value',shareProvider[i].toLowerCase());
	shareDiv.setAttribute('name',"settings[enable"+shareProvider[i].toLowerCase()+"]");
	shareDiv.setAttribute('onchange',"loginRadiusHorizontalSharingLimit(this);loginRadiusHorizontalRearrangeProviderList(this);");
	shareDiv.setAttribute('style',"float: left !important;");	
	var socialShareLabel = document.createElement('label');
	socialShareLabel.innerHTML = shareProvider[i]; 
	socialShareLabel.setAttribute('class',"socialTitle");
	horizontalSharingProvidersContainer.appendChild(shareDiv);
	horizontalSharingProvidersContainer.appendChild(socialShareLabel);
	SSP.appendChild(horizontalSharingProvidersContainer);	
}

var SCP = document.getElementById('counterhprovider');	

for(var i = 0; i < counterProvider.length; i++)
{
	var horizontalCounterProvidersContainer = document.createElement('div');
	horizontalCounterProvidersContainer.setAttribute('class','loginRadiusCounterProviders');
	var counterDiv = document.createElement('input');
	counterDiv.setAttribute('type','checkbox');
	counterDiv.setAttribute('id','horizontalcounterid'+counterProvider[i].toLowerCase());	
	counterDiv.setAttribute('value',counterProvider[i]);
	counterDiv.setAttribute('name',"horizontalcounter[]");
	counterDiv.setAttribute('style',"float: left !important;");
	var socialCounterLabel = document.createElement('label');
	socialCounterLabel.innerHTML = counterProvider[i]; 
	socialCounterLabel.setAttribute('class',"socialTitle");
	horizontalCounterProvidersContainer.appendChild(counterDiv);
	horizontalCounterProvidersContainer.appendChild(socialCounterLabel);
	SCP.appendChild(horizontalCounterProvidersContainer);		
}

var SSVP = document.getElementById('sharevprovider');	
for(var i = 0; i < shareProvider.length; i++)
{
	var verticalSharingProvidersContainer = document.createElement('div');
	verticalSharingProvidersContainer.setAttribute('class','loginRadiusProviders');
	var shareDiv = document.createElement('input');
	shareDiv.setAttribute('type','checkbox');
	shareDiv.setAttribute('id','verticalsharingid'+shareProvider[i].toLowerCase());		
	shareDiv.setAttribute('value',shareProvider[i].toLowerCase());
	<!--shareDiv.setAttribute('name',"settings[enable"+shareProvider[i].toLowerCase()+"]");-->
	shareDiv.setAttribute('onchange',"loginRadiusVerticalSharingLimit(this);loginRadiusVerticalRearrangeProviderList(this);");
	shareDiv.setAttribute('style',"float: left !important;");	
	var socialShareLabel = document.createElement('label');
	socialShareLabel.innerHTML = shareProvider[i]; 
	socialShareLabel.setAttribute('class',"socialTitle");
	verticalSharingProvidersContainer.appendChild(shareDiv);
	verticalSharingProvidersContainer.appendChild(socialShareLabel);
	SSVP.appendChild(verticalSharingProvidersContainer);
}
var SCVP = document.getElementById('countervprovider');	

for(var i = 0; i < counterProvider.length; i++)
{
	var verticalCounterProvidersContainer = document.createElement('div');
	verticalCounterProvidersContainer.setAttribute('class','loginRadiusCounterProviders');
	var counterDiv = document.createElement('input');
	counterDiv.setAttribute('type','checkbox');
	counterDiv.setAttribute('id','verticalcounterid'+counterProvider[i].toLowerCase());	
	counterDiv.setAttribute('value',counterProvider[i]);
	counterDiv.setAttribute('name',"verticalcounter[]");
	counterDiv.setAttribute('style',"float: left !important;");
	var socialCounterLabel = document.createElement('label');
	socialCounterLabel.innerHTML = counterProvider[i]; 
	socialCounterLabel.setAttribute('class',"socialTitle");
	verticalCounterProvidersContainer.appendChild(counterDiv);
	verticalCounterProvidersContainer.appendChild(socialCounterLabel);
	SCVP.appendChild(verticalCounterProvidersContainer);
}
	for(var i = 0; i < horshareChecked.length; i++){
		if(!horshareChecked[i].checked){
			document.getElementById('horizontalsharingid'+horshareChecked[i].toLowerCase()).setAttribute('checked', 'checked');
		}
	}
	for(var i = 0; i < vershareChecked.length; i++){
		if(!vershareChecked[i].checked){
			document.getElementById('verticalsharingid'+vershareChecked[i].toLowerCase()).setAttribute('checked', 'checked');
		}
	}
	for(var i = 0; i < horcounterChecked.length; i++){
		if(!horcounterChecked[i].checked){
			document.getElementById('horizontalcounterid'+horcounterChecked[i].toLowerCase()).setAttribute('checked', 'checked');
		}
	}
	for(var i = 0; i < vercounterChecked.length; i++){
		if(!vercounterChecked[i].checked){
			document.getElementById('verticalcounterid'+vercounterChecked[i].toLowerCase()).setAttribute('checked', 'checked');
		}
	}
};
</script>
<form action="<?php echo JRoute::_('index.php?option=com_socialloginandsocialshare&view=socialloginandsocialshare&layout=default'); ?>" method="post" name="adminForm">
<div>
  <div style="float:left; width:70%;">
    <div>
	  <fieldset class="sociallogin_form sociallogin_form_main" style="background:#EAF7FF; border: 1px solid #B3E2FF;">
      <div class="row row_title" style="color: #000000; font-weight:normal;">
        <?php echo JText::_('COM_SOCIALLOGIN_THANK'); ?>
      </div>
      <div class="row" style="width:90%; line-height:160%;">
        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK'); ?> 
      </div>
      <div class="row" style="width:90%; line-height:160%;">
        <?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_TWO'); ?> 
      </div>
      <div class="row row_button" style="background:none; border:none;">
        <div class="button2-left">
          <div class="blank" style="margin:0 0 10px 0;">
            <a class="modal" href="http://www.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_THANK_BLOCK_FIVE'); ?></a>
          </div>
		</div>
      </div>
      </fieldset>
    </div>
<?php	$pane = JPane::getInstance('tabs', array('startOffset'=>2, 'allowAllClose'=>true, 'opacityTransition'=>true, 'duration'=>600)); 
        echo $pane->startPane( 'pane' );
        echo $pane->startPanel( JText::_('COM_SOCIALLOGIN_PANEL_LOGIN'), 'panel1' );
?>
	<!-- Form Box -->
  <div>
<table class="form-table sociallogin_table">
  <tr>
    <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING'); ?></small></th>
  </tr>
  <tr >
    <td colspan="2" ><span class="subhead"> <?php echo JText::_('COM_ENABLE_DISABLE_SOCIALLOGIN'); ?></span>
	  <br/><br />
      <?php  $enableLogin = "";
           $disableLogin= "";
           $enableSocialLogin = (isset($this->settings['enableSocialLogin'])  ? $this->settings['enableSocialLogin'] : "");
           if ($enableSocialLogin == '0') $disableLogin = "checked='checked'";
           else if ($enableSocialLogin == '1') $enableLogin = "checked='checked'";
           else $enableLogin = "checked='checked'";?>
     <input name="settings[enableSocialLogin]" type="radio" value="1" <?php echo $enableLogin; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> 
	 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <input name="settings[enableSocialLogin]" type="radio" value="0" <?php echo $disableLogin; ?> /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> 
	</td>
  </tr>
</table>

<table class="form-table sociallogin_table">
  <tr>
    <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_API'); ?></small></th>
  </tr>
  <tr >
    <input id="connection_url" type="hidden" value="<?php echo JURI::root();?>" />
    <td colspan="2" ><span class="subhead"> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY_DESC'); ?></span>
	  <br/><br />
      <?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_KEY'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input size="60" type="text" name="settings[apikey]" id="apikey" value="<?php echo (isset ($this->settings ['apikey']) ? htmlspecialchars ($this->settings ['apikey']) : ''); ?>" />
      <br /><br />
	  <?php echo JText::_('COM_SOCIALLOGIN_SETTING_API_SECRET'); ?>	&nbsp;&nbsp;<input size="60" type="text" name="settings[apisecret]" id="apisecret" value="<?php echo (isset ($this->settings ['apisecret']) ? htmlspecialchars ($this->settings ['apisecret']) : ''); ?>" />
	</td>
  </tr>
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI'); ?></span>
      <br /><br />
      <?php   $useapi_curl = "";
              $useapi_fopen = "";
              $useapi = (isset($this->settings['useapi']) ? $this->settings['useapi'] : "");
              if ($useapi == '1' ) $useapi_curl = "checked='checked'";
              else if ($useapi == '0') $useapi_fopen = "checked='checked'";
              else $useapi_curl = "checked='checked'";?>
     <input name="settings[useapi]" id = "curl" type="radio"  <?php echo $useapi_curl;?>value="1" /> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI_CURL'); ?> 
	 <br />
     <input name="settings[useapi]" id = "fsockopen" type="radio" <?php echo $useapi_fopen;?>value="0" /> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_USEAPI_FOPEN');     ?> 
    </td>
  </tr>
  <tr class="row_white">
    <td>
      <div class="row row_button">
        <div class="button2-left">
          <div class="blank">
            <a class="modal" href="javascript:void(0);" onclick="MakeRequest();"><b style="color:#C91F00 !important;"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_VERIFYAPI'); ?></b>
			</a>
		  </div>
        </div>
      </div>
    </td>
    <td><div id="ajaxDiv" style="font-weight:bold;"></div></td>
  </tr>
</table>

<!-- Interface customization options -->
<table class="form-table sociallogin_table">
  <tr>
    <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_INTERFACE_CUSTOMIZATION'); ?></small></th>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"> <?php echo JText::_('COM_SOCIALLOGIN_ICON_SIZE'); ?></span>
      <br /><br />
    <input name="settings[iconSize]" type="radio" <?php echo isset($this->settings['iconSize']) && $this->settings['iconSize'] == 'medium' ? 'checked' : '';?> value="medium"  /> <?php echo JText::_('COM_SOCIALLOGIN_MEDIUM'); ?>&nbsp;&nbsp;&nbsp;
    <input name="settings[iconSize]" type="radio" <?php echo !isset($this->settings['iconSize']) || $this->settings['iconSize'] == 'small' ? 'checked' : '';?> value="small"  /> <?php echo JText::_('COM_SOCIALLOGIN_SMALL'); ?>
    </td>
  </tr>
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_ICONS_PER_ROW'); ?></span>
      <br /><br />
      <input name="settings[iconsPerRow]" type="text" value="<?php echo isset($this->settings['iconsPerRow']) ? trim($this->settings['iconsPerRow']) : '' ; ?>" />
    </td>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_INTERFACE_BACKGROUND'); ?> <a style="text-decoration:none" href="javascript:void(0)" title="<?php echo JText::_('COM_SOCIALLOGIN_INTERFACE_BACKGROUND_HELP'); ?>">(?)</a></span>
      <br /><br />
      <input name="settings[interfaceBackground]" type="text" value="<?php echo isset($this->settings['interfaceBackground']) ? trim($this->settings['interfaceBackground']) : '' ; ?>" />
    </td>
  </tr>
</table>
<!-- Interface customization options -->

<table class="form-table sociallogin_table">
  <tr>
    <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC'); ?></small></th>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_BASIC_REDIRECT_DESC'); ?></span><br /><br />
      <?php $db = JFactory::getDBO();
      $query = "SELECT m.id, m.title,m.level,mt.menutype FROM #__menu AS m INNER JOIN #__menu_types AS mt ON mt.menutype = m.menutype WHERE mt.menutype = m.menutype AND m.published = '1' ORDER BY mt.menutype,m.level";
      $db->setQuery($query);
      $rows = $db->loadObjectList();?>
      <?php $setredirct = (isset($this->settings['setredirct']) ? $this->settings['setredirct'] : "");?>
      <select id="setredirct" name="settings[setredirct]">
        <option value="" selected="selected">---Default---</option>
        <?php foreach ($rows as $row) {?>
        <option <?php if ($row->id == $setredirct) { echo " selected=\"selected\""; } ?>value="<?php echo $row->id;?>" >
          <?php echo '<b>'.$row->menutype.'</b>';
          if ($row->level == 1) { echo '-';}
          if($row->level == 2) { echo '--';}
          if($row->level == 3) { echo '---';}
          if($row->level == 4) { echo '----';}
          if($row->level == 5) { echo '-----';}
            echo $row->title;?>
        </option>
      <?php }?>
      </select>
    </td>
  </tr>	
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_LINK_DESC'); ?></span><br /><br />
        <?php $yeslink = "";
		      $notlink = "";
              $linkaccount = (isset($this->settings['linkaccount'])  ? $this->settings['linkaccount'] : "");
              if ($linkaccount == '1') $yeslink = "checked='checked'";
              else if ($linkaccount == '0') $notlink = "checked='checked'";
              else $yeslink = "checked='checked'";?>
        <input name="settings[linkaccount]" type="radio" <?php echo $yeslink;?> value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_LINK_YES'); ?><br />
        <input name="settings[linkaccount]" type="radio" <?php echo $notlink;?>value="0"   /> <?php echo JText::_('COM_SOCIALLOGIN_LINK_NO'); ?> 
    </td>
  </tr>
  <?php if (JPluginHelper::isEnabled('system', 'k2')) {?>
  <tr>
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2_DESC'); ?> </span><br /><br />
    <?php echo JText::_('COM_SOCIALLOGIN_SETTING_K2'); ?> <input type="text"  name="settings[k2group]" size="2" value="<?php echo (isset ($this->settings ['k2group']) ? htmlspecialchars ($this->settings ['k2group']) : '2'); ?>" />
    </td>
  </tr>
  <?php }?>
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_DESC'); ?></span><br /><br />
      <?php $yessendemail = "";
            $notsendemail = "";
            $sendemail = (isset($this->settings['sendemail'])  ? $this->settings['sendemail'] : "");
            if ($sendemail == '1') $yessendemail = "checked='checked'";
            else if ($sendemail == '0') $notsendemail = "checked='checked'";
            else $yessendemail = "checked='checked'";?>
      <input name="settings[sendemail]" type="radio" <?php echo $yessendemail;?> value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="settings[sendemail]" type="radio" <?php echo $notsendemail;?> value="0"   /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> 
    </td>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_REQUIRED_DESC'); ?></span><br /><br />
    <?php $yesdummyemail = "";
          $notdummyemail = "";
          $dummyemail = (isset($this->settings['dummyemail'])  ? $this->settings['dummyemail'] : "");
          if ($dummyemail == '0') $yesdummyemail = "checked='checked'";
          else if ($dummyemail == '1') $notdummyemail = "checked='checked'";
          else $notdummyemail = "checked='checked'";?>
   <input name="settings[dummyemail]" type="radio"  <?php echo $notdummyemail;?>value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_EMAIL_YES'); ?><br />
   <input name="settings[dummyemail]" type="radio" <?php echo $yesdummyemail;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_EMAIL_NO'); ?>
   </td>
  </tr>

<!-------- Email Popup Setting ----------->
  <tr class="row_white">
	  <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_TITLE'); ?></span><br /><br />
	  <?php 
	  $emailtitle = "";
	  $emailtitle = (!empty($this->settings['emailtitle'])?$this->settings['emailtitle']:JText::_('COM_SOCIALLOGIN_POPUP_HEAD'));
	  ?>
	  <input name="settings[emailtitle]" size="60" type="text" id="emailtitle"  value="<?php echo $emailtitle; ?>"  />
	  </td>
  </tr>
  <tr>
	  <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_EMAIL_TITLE_MESSAGE'); ?></span><br /><br />
	  <?php
	  $emailrequiredmessage = "";
	  $emailrequiredmessage = (!empty($this->settings['emailrequiredmessage'])?$this->settings['emailrequiredmessage']:JText::_('COM_SOCIALLOGIN_POPUP_MSG')." %s ".JText::_('COM_SOCIALLOGIN_POPUP_MSGONE')." ".JText::_('COM_SOCIALLOGIN_POPUP_MSGTWO'));
	  ?>
	  <input name="settings[emailrequiredmessage]" size="60" type="text" id="emailrequiredmessage"  value="<?php echo $emailrequiredmessage; ?>"  />
	  </td>
  </tr>
  <tr class="row_white"> 
	  <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ERROR_EMAIL_TITLE_MESSAGE'); ?></span><br /><br />
	  <?php  
	  $emailinvalidmessage = "";
	  $emailinvalidmessage = (!empty($this->settings['emailinvalidmessage'])?$this->settings['emailinvalidmessage']:JText::_('COM_SOCIALLOGIN_EMAIL_INVALID'));
	  ?>
	  <input name="settings[emailinvalidmessage]" size="60" type="text" id="emailinvalidmessage"  value="<?php echo $emailinvalidmessage; ?>"  />
	  </td>
  </tr> 
<!------- END Email Popup Setting --------->

</table>
<table class="form-table sociallogin_table">
  <tr>
    <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FRONT'); ?></small></th>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_NAME_DESC'); ?></span>
      <br /><br />
      <?php  $showonlyname = "";
           $showusername = "";
           $showname = (isset($this->settings['showname'])  ? $this->settings['showname'] : "");
           if ($showname == '0') $showonlyname = "checked='checked'";
           else if ($showname == '1') $showusername = "checked='checked'";
           else $showonlyname = "checked='checked'";?>
    <input name="settings[showname]" type="radio" <?php echo $showonlyname;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NAME'); ?>&nbsp;&nbsp;&nbsp;
    <input name="settings[showname]" type="radio"  <?php echo $showusername;?>value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_USERNAME'); ?>
      
    </td>
  </tr>
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_FORM_DESC'); ?></span>
      <br /><br />
      <?php $yesshowwithicons = "";
            $notshowwithicons = "";
            $showwithicons = (isset($this->settings['showwithicons'])  ? $this->settings['showwithicons'] : "");
            if ($showwithicons == '1') $yesshowwithicons = "checked='checked'";
            else if ($showwithicons == '0') $notshowwithicons = "checked='checked'";
            else $yesshowwithicons = "checked='checked'";?>
      <input name="settings[showwithicons]" type="radio"  <?php echo $yesshowwithicons;?>value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="settings[showwithicons]" type="radio" <?php echo $notshowwithicons;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> 
    </td>
  </tr>
  <tr>
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_DESC'); ?></span>
      <br /><br />
      <?php $topshowicons = "";
          $botshowicons = "";
          $showicons = (isset($this->settings['showicons']) ? $this->settings['showicons'] : "");
          if ($showicons == '0') $topshowicons = "checked='checked'";
          else if ($showicons == '1') $botshowicons = "checked='checked'";
          else $topshowicons = "checked='checked'";?>
      <input name="settings[showicons]" type="radio" <?php echo $topshowicons;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_TOP'); ?>&nbsp;&nbsp;&nbsp;
      <input name="settings[showicons]" type="radio"  <?php echo $botshowicons;?>value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_SETTING_ICONS_BOT'); ?>
    </td>
  </tr>
  <tr class="row_white">
    <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_GREETING_DESC'); ?></span>
      <br /><br />
      <?php   $yesshowlogout = "";
              $notshowlogout = "";
              $showlogout = (isset($this->settings['showlogout'])  ? $this->settings['showlogout'] : "");
              if ($showlogout == '1') $yesshowlogout = "checked='checked'";
              else if ($showlogout == '0') $notshowlogout = "checked='checked'";
              else $yesshowlogout = "checked='checked'";?>
	  <input name="settings[showlogout]" type="radio" <?php echo $yesshowlogout;?> value="1" /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="settings[showlogout]" type="radio" <?php echo $notshowlogout;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> 
    </td>
  </tr>
</table>
<table class="form-table sociallogin_table">
<tr>
<th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_PROFILE_DATA_OPTIONS'); ?></th>
</tr>
<tr>
<td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SETTING_USERPROFILEDATE_UPDATE');?></span><br /><br />
<?php $yesuserdata = "";
$notuserdata = "";
$updateuserdata = (isset($this->settings['updateuserdata']) ? $this->settings['updateuserdata'] : "");
if ($updateuserdata == '1') $yesuserdata = "checked='checked'";
else if ($updateuserdata == '0') $notuserdata = "checked='checked'";
else $yesuserdata = "checked='checked'";?>
<input name="settings[updateuserdata]" type="radio" <?php echo $yesuserdata;?> value="1" /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input name="settings[updateuserdata]" type="radio" <?php echo $notuserdata;?> value="0" /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?>
</td>
</tr>
</table>

</div>
<?php echo $pane->endPanel();?>

<!-- social share -->
<?php echo $pane->startPanel( JText::_('COM_SOCIALLOGIN_PANEL_SHARE'), 'panel2' );?>
<div>
  <table class="form-table sociallogin_table">
    <tr>
      <th class="head" colspan="2"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE'); ?></small></th>
    </tr>
    <tr>
      <td colspan="2" ><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ENABLE'); ?></span><br /><br />
        <?php 	
		  $yesenableshare = "";
          $noenableshare = "";
          $enableshare = (isset($this->settings['enableshare']) ? $this->settings['enableshare'] : "");
          if ($enableshare == '0') $noenableshare = "checked='checked'";
          else if ($enableshare == '1') $yesenableshare = "checked='checked'";
          else $yesenableshare = "checked='checked'";?>
      <input name="settings[enableshare]" type="radio" <?php echo $yesenableshare;?>value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <input name="settings[enableshare]" type="radio"  <?php echo $noenableshare;?>value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?>
		
	  </td>
    </tr>
	<tr class="row_white">
       <td colspan="2" >
	   <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME'); ?></span><br /><br />
	    <?php $hori32 = "";
	        $hori16 = "";
			$horithemelarge = "";
			$horithemesmall = "";			
			$chori32 = "";
	        $chori16 = "";			
            $choosehorizontalshare = (isset($this->settings['choosehorizontalshare']) ? $this->settings['choosehorizontalshare'] : "");
            if ($choosehorizontalshare == '0' ) $hori32 = "checked='checked'";
            else if ($choosehorizontalshare == '1' ) $hori16 = "checked='checked'";
			else if ($choosehorizontalshare == '2' ) $horithemelarge = "checked='checked'";
			else if ($choosehorizontalshare == '3' ) $horithemesmall = "checked='checked'";
			else if ($choosehorizontalshare == '4' ) $chori16 = "checked='checked'";
            else if ($choosehorizontalshare == '5' ) $chori32 = "checked='checked'";			
            else $hori32 = "checked='checked'";
			
            $vertibox32 = "";
			$vertibox16 = "";
            $cvertibox32 = "";
			$cvertibox16 = "";
            $chooseverticalshare = (isset($this->settings['chooseverticalshare']) ? $this->settings['chooseverticalshare'] : "");
			if ($chooseverticalshare == '0' ) $vertibox32 = "checked='checked'";
			else if ($chooseverticalshare == '1' ) $vertibox16 = "checked='checked'";			
			else if ($chooseverticalshare == '2' ) $cvertibox32 = "checked='checked'";
			else if ($chooseverticalshare == '3' ) $cvertibox16 = "checked='checked'";  
			else $vertibox32 = "checked='checked'";?> 
                     
	     <a id="mymodal1" href="javascript:void(0);" onclick="Makehorivisible();" style="color: #00CCFF;"><b><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORI'); ?></b></a> &nbsp;|&nbsp; 
	     <a id="mymodal2" href="javascript:void(0);" onclick="Makevertivisible();" style="color: #000000;"><b><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_VERTICAL'); ?></b></a>
	     <div style="border:#dddddd 1px solid; padding:10px; background:#FFFFFF; margin:10px 0 0 0;">
	     <span id = "arrow" style="position:absolute; border-bottom:8px solid #ffffff; border-right:8px solid transparent; border-left:8px solid transparent; margin:-18px 0 0 2px;"></span>
	     <div id="sharehorizontal" style="display:block;">
         
         <div style="overflow:auto; background:#EBEBEB; padding:10px;">
		 <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORIZONTAL'); ?></span><br/>
       <?php $enablehshare = "";
             $disablehshare = "";
             $sharehorizontal = (isset($this->settings['sharehorizontal'])  ? $this->settings['sharehorizontal'] : "");
             if ($sharehorizontal == '1') $enablehshare = "checked='checked'";
             else if ($sharehorizontal == '0') $disablehshare = "checked='checked'";
             else $enablehshare = "checked='checked'";?>
       <input name="settings[sharehorizontal]" type="radio"  <?php echo $enablehshare;?> value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <input name="settings[sharehorizontal]" type="radio" <?php echo $disablehshare;?> value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> </div><br />
      
       <!--display social sharing title on top of interface-->
       <div style="overflow:auto; background:#FFFFFF; padding:10px;">
       <?php
	   $beforesharetitle="";
       $beforesharetitle = (isset($this->settings['beforesharetitle']) ? $this->settings['beforesharetitle'] : "");
	   ?>
	   <span class="subhead"><?php echo JText::_('COM_SOCIALSHARE_TITLE'); ?></span><br/>
	   <input name="settings[beforesharetitle]" type="text" id="beforesharetitle"  value="<?php echo $beforesharetitle; ?>"  /></div><br/>
	   <div style="overflow:auto; background:#EBEBEB; padding:10px;"><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_HORIZONTAL_THEMES'); ?></span><br /><br />
       
       <!--socialsharing interface theme-->
	     <input name="settings[choosehorizontalshare]" id = "hori32" onclick="createhorsharprovider();" type="radio"  <?php echo $hori32;?>value="0" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/horizonSharing32.png"?>' /><br /><br />
         <input name="settings[choosehorizontalshare]" id = "hori16" onclick="createhorsharprovider();" type="radio" <?php echo $hori16;?>value="1" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/horizonSharing16.png"?>' /><br /><br />
         <input name="settings[choosehorizontalshare]" id = "horithemelarge" onclick="singleimgsharprovider();" type="radio" <?php echo $horithemelarge;?>value="2" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/single-image-theme-large.png"?>' /><br /><br />
         <input name="settings[choosehorizontalshare]" id = "horithemesmall" onclick="singleimgsharprovider();" type="radio" <?php echo $horithemesmall;?>value="3" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/single-image-theme-small.png"?>' /><br /><br />
         <input name="settings[choosehorizontalshare]" id = "chori16" onclick="createhorcounprovider();" type="radio"  <?php echo $chori16;?>value="4" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/hybrid-horizontal-horizontal.png"?>' /><br /><br />
      <input name="settings[choosehorizontalshare]" id = "chori32" onclick="createhorcounprovider();" type="radio" <?php echo $chori32;?>value="5" style="margin: 2px 10px 0 0; display: block; float: left !important;" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/hybrid-horizontal-vertical.png"?>' /><br /></div>
      
      <!--socialshare position select-->
      <div style="overflow:auto; background:#FFFFFF; padding:10px;">
	  <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION'); ?></span><br/><br/>
       <?php $shareontop = "";
             $shareonbottom = "";
			 $shareOnTopPos = (isset($this->settings['shareOnTopPos']) == 'on'  ? 'on' : 'off');
        	if ($shareOnTopPos == 'on'){ $shareontop = "checked='checked'";}
			$shareOnBottomPos = (isset($this->settings['shareOnBottomPos']) == 'on'  ? 'on' : 'off');
       		if ($shareOnBottomPos == 'on'){ $shareonbottom = "checked='checked'";}
			?>
             <input name="settings[shareOnTopPos]" type="checkbox"  <?php echo $shareontop;?> value="on"  /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_TOP'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
             <input name="settings[shareOnBottomPos]" type="checkbox"  <?php echo $shareonbottom;?> value="on"  /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_POSITION_BOTTOM'); ?></div><br />
       
       <!--select rearrange icon for social share-->
       <div style="overflow:auto; background:#EBEBEB; padding:10px;display:<?php if($choosehorizontalshare == '' || $choosehorizontalshare == '0' || $choosehorizontalshare == '1'){echo 'block';}else{echo 'none';}?>;" id="lrhorizontalsharerearange">
       <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_REARRANGE'); ?></span><br />
     <ul id="horsortable" style="float:left; padding-left:0;">
			<?php 
            $horizontal_provider = '';
            $horizontal_rearrange = (isset($this->settings['horizontal_rearrange']) ? $this->settings['horizontal_rearrange'] : "");
            $horizontal_rearrange = unserialize($horizontal_rearrange);
            if (empty($horizontal_rearrange)) {
              $horizontal_rearrange[] = 'facebook';
              $horizontal_rearrange[] = 'googleplus';
              $horizontal_rearrange[] = 'twitter';
              $horizontal_rearrange[] = 'linkedin';
              $horizontal_rearrange[] = 'pinterest';
            }
                foreach($horizontal_rearrange  as $horizontal_provider){
                    ?>
                    <li title="<?php echo $horizontal_provider ?>" id="lrhorizontal_<?php echo strtolower($horizontal_provider); ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($horizontal_provider); ?> dragcursor">
                    <input type="hidden" name="horizontal_rearrange[]" value="<?php echo strtolower($horizontal_provider); ?>" />
                    </li>
                    <?php }	?>
			  </ul>
              </div>
         
         <!--select counter provider checkboxes-->
         <div style="overflow:auto; background:#EBEBEB; padding:10px;display:<?php if($choosehorizontalshare == '4' || $choosehorizontalshare == '5' ){echo 'block';}else{echo 'none';}?>;" id="lrhorizontalcounterprovider">
         <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br /><br />
         <div id="counterhprovider" class="row_white"></div>
         </div>
         
         <!--select share provider checkboxes-->
         <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php if($choosehorizontalshare == '' || $choosehorizontalshare == '0' || $choosehorizontalshare == '1'){echo 'block';}else{echo 'none';}?>;" id="lrhorizontalshareprovider">
		 <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br /><br />
		<div id="loginRadiusHorizontalSharingLimit" style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIALLOGIN_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
        <div id="sharehprovider" class="row_white"></div>
        </div>
        
        <!--select page for socialsharing-->
        <div style="overflow:auto; background:<?php if($choosehorizontalshare == '' || $choosehorizontalshare == '' || $choosehorizontalshare == '0' || $choosehorizontalshare == '1' || $choosehorizontalshare == '2' || $choosehorizontalshare == '3'){ echo '#EBEBEB';}else{echo '#FFFFFF';}?>; padding:10px;" id="horizontalPageSelect">
        <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES'); ?></span><br /><br />
     <?php $db = JFactory::getDBO();
           $query = "SELECT id, title FROM #__content WHERE state = '1' ORDER BY ordering";
           $db->setQuery($query);
           $rows = $db->loadObjectList();
     ?>
     <?php $horizontal_articles = (isset($this->settings['h_articles']) ? $this->settings['h_articles'] : "");
           $horizontal_articles = unserialize($horizontal_articles);?>
      <select id="h_articles[]" name="h_articles[]" multiple="multiple" style="width:400px;">
      <?php foreach ($rows as $row) {?>
        <option <?php if (!empty($horizontal_articles)) {
              foreach ($horizontal_articles as $key=>$value) {
                if ($row->id == $value) { 
                  echo " selected=\"selected\""; 
                } 
              }
            }?>value="<?php echo $row->id;?>" >
            <?php echo $row->title;?>
        </option>
<?php }?>
     </select></div>
         </div>
         <div id="sharevertical" style="display:none;">
         <div style="overflow:auto; background:#EBEBEB; padding:10px;">
         
         <!--enable vertical sharing-->
		 <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ENABLE_VERTICAL'); ?></span><br/>
       <?php $enablevshare = "";
             $disablevshare = "";
             $sharevertical = (isset($this->settings['sharevertical'])  ? $this->settings['sharevertical'] : "");
             if ($sharevertical == '1') $enablevshare = "checked='checked'";
             else if ($sharevertical == '0') $disablevshare = "checked='checked'";
             else $enablevshare = "checked='checked'";?>
       <input name="settings[sharevertical]" type="radio"  <?php echo $enablevshare;?> value="1"  /> <?php echo JText::_('COM_SOCIALLOGIN_YES'); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	   <input name="settings[sharevertical]" type="radio" <?php echo $disablevshare;?> value="0"  /> <?php echo JText::_('COM_SOCIALLOGIN_NO'); ?> </div><br />
       <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_VERTICAL_THEMES'); ?></span><br /><br />
         
         <!--vertical socialshare theme-->
         <input name="settings[chooseverticalshare]" id = "vertibox32" onclick="createversharprovider();" type="radio"  <?php echo $vertibox32;?> value="0" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/32VerticlewithBox.png"?>' style="vertical-align:top;" />
         <input name="settings[chooseverticalshare]" id = "vertibox16" onclick="createversharprovider();" type="radio" <?php echo $vertibox16;?> value="1" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/16VerticlewithBox.png"?>' style="vertical-align:top;" />
         <input name="settings[chooseverticalshare]" id = "cvertibox32" onclick="createvercounprovider();" type="radio"  <?php echo $cvertibox32;?> value="2" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/hybrid-verticle-horizontal.png"?>' style="vertical-align:top;" />
      <input name="settings[chooseverticalshare]" id = "cvertibox16" onclick="createvercounprovider();" type="radio" <?php echo $cvertibox16;?> value="3" /> <img src = '<?php echo "components/com_socialloginandsocialshare/assets/img/hybrid-verticle-vertical.png"?>' style="vertical-align:top;" /><br/><br/>
      
      	<!--position for social sharing for vertical inter face-->
         <div style="overflow:auto; background:#EBEBEB; padding:10px;">
         <p style="margin:0 0 6px 0; padding:0px;"><span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION'); ?></span></p>
         <?php $topleft = "";
	        $topright = "";
			$bottomleft = "";
			$bottomright = "";
			$verticalsharepos = (isset($this->settings['verticalsharepos']) ? $this->settings['verticalsharepos'] : "");
			$verticalsharetopoffset=(isset($this->settings['verticalsharetopoffset']) ? $this->settings['verticalsharetopoffset'] : '');
            if ($verticalsharepos == '0' ) $topleft = "checked='checked'";
            else if ($verticalsharepos == '1' ) $topright = "checked='checked'";
			else if ($verticalsharepos == '2' ) $bottomleft = "checked='checked'";
			else if ($verticalsharepos == '3' ) $bottomright = "checked='checked'";
			else $topleft = "checked='checked'";?>
        <input name="settings[verticalsharepos]" id = "topleft" type="radio"  <?php echo $topleft;?>value="0" /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_TOPL'); ?><br /> 
        <input name="settings[verticalsharepos]" id = "topright" type="radio" <?php echo $topright;?>value="1" /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_TOPR'); ?> <br />
        <input name="settings[verticalsharepos]" id = "bottomleft" type="radio" <?php echo $bottomleft;?>value="2" /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_BOTTOML'); ?><br /> 
        <input name="settings[verticalsharepos]" id = "bottomright" type="radio" <?php echo $bottomright;?>value="3" /> <?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_THEME_POSITION_BOTTOMR'); ?><br /></div>
        
        <!--socialsharing top offset-->
        <div style="overflow:auto; background:#FFFFFF; padding:10px;">
		<span class="subhead"><?php echo JTEXT::_('COM_SOCIALLOGIN_SOCIAL_SHARE_TOP_OFFSET'); ?></span><a href="javascript:void(0);" style="text-decoration:none;" title="<?php echo JTEXT::_('COM_TOP_OFFSET_HELP'); ?>" >(?)</a><br/><br/><input type="text" id="topoffset" name="settings[verticalsharetopoffset]" value="<?php echo $verticalsharetopoffset; ?>" >
         </div>
         
         <!--socialsharing rearrange for vertical-->
         <div style="overflow:auto; background:#EBEBEB; padding:10px;display:<?php if($chooseverticalshare == '' || $chooseverticalshare == '0' || $chooseverticalshare == '1' ){echo 'block';}else{echo 'none';}?>;" id="lrverticalsharerearange">
       <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_REARRANGE'); ?></span><br />
     <ul id="versortable" style="float:left; padding-left:0;">
						<?php 
						$vertical_provider = '';
						$vertical_rearrange = (isset($this->settings['vertical_rearrange']) ? $this->settings['vertical_rearrange'] : "");
						$vertical_rearrange = unserialize($vertical_rearrange);
						if (empty($vertical_rearrange)) {
						  $vertical_rearrange[] = 'facebook';
						  $vertical_rearrange[] = 'googleplus';
						  $vertical_rearrange[] = 'twitter';
						  $vertical_rearrange[] = 'linkedin';
						  $vertical_rearrange[] = 'pinterest';
						}
							foreach($vertical_rearrange  as $vertical_provider){
								?>
								<li title="<?php echo $vertical_provider ?>" id="lrvertical_<?php echo strtolower($vertical_provider); ?>" class="lrshare_iconsprite32 lrshare_<?php echo strtolower($vertical_provider); ?> dragcursor">
								<input type="hidden" name="vertical_rearrange[]" value="<?php echo strtolower($vertical_provider); ?>" />
								</li>
								<?php
							}						
						?>
			  </ul>
              </div>
              
          <!--select socialsharing checkboxed for vertical interface-->
         <div style="overflow:auto; background:#EBEBEB; padding:10px;display:<?php if($chooseverticalshare == '2' || $chooseverticalshare == '3' ){echo 'block';}else{echo 'none';}?>;" id="lrverticalcounterprovider">
         <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br /><br />
         <div id="countervprovider" class="row_white"></div>
         </div>
         
         <!--social sharing for vertical interface-->
         <div style="overflow:auto; background:#FFFFFF; padding:10px;display:<?php if($chooseverticalshare == '' || $chooseverticalshare == '0' || $chooseverticalshare == '1' ){echo 'block';}else{echo 'none';}?>;" id="lrverticalshareprovider">
		 <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_NETWORKS'); ?></span><br /><br />
		<div id="loginRadiusVerticalSharingLimit" style="color: red; display: none; margin-bottom: 5px;"><?php echo JTEXT::_('COM_SOCIALLOGIN_SOCIAL_SHARE_PROVIDER_LIMITE'); ?></div>
        <div id="sharevprovider" class="row_white"></div>
        </div>
        
        <!-- select page for vertical sharing interface-->
        <div style="overflow:auto; background:<?php if($chooseverticalshare == '' || $chooseverticalshare == '0' || $chooseverticalshare == '1'){ echo '#EBEBEB';}else{echo '#FFFFFF';}?>; padding:10px;" id="verticalPageSelect">
        <span class="subhead"><?php echo JText::_('COM_SOCIALLOGIN_SOCIAL_SHARE_ARTICLES'); ?></span><br /><br />
     <?php $db = JFactory::getDBO();
           $query = "SELECT id, title FROM #__content WHERE state = '1' ORDER BY ordering";
           $db->setQuery($query);
           $rows = $db->loadObjectList();
     ?>
     <?php $vertical_articles = (isset($this->settings['v_articles']) ? $this->settings['v_articles'] : "");
           $vertical_articles = unserialize($vertical_articles);?>
      <select id="v_articles[]" name="v_articles[]" multiple="multiple" style="width:400px;">
      <?php foreach ($rows as $row) {?>
        <option <?php if (!empty($vertical_articles)) {
              foreach ($vertical_articles as $key=>$value) {
                if ($row->id == $value) { 
                  echo " selected=\"selected\""; 
                } 
              }
            }?>value="<?php echo $row->id;?>" >
            <?php echo $row->title;?>
        </option>
<?php }?>
     </select></div></div>
     </div>
       </td>
     </tr>   
</table>
</div>
<?php echo $pane->endPanel();?>
<!-- End social share -->

</div>
<div style="float:right; width:29%;">
<!-- Help Box --> 
<div style="background:#EAF7FF; border: 1px solid #B3E2FF; overflow:auto; margin:0 0 10px 0;">
	<h3 style="border-bottom:#000000 1px solid; margin:0px; padding:0 0 6px 0; border-bottom: 1px solid #B3E2FF; color: #000000; margin:10px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP'); ?></h3>
	<ul class="help_ul">
  <li><a href="http://support.loginradius.com/customer/portal/articles/1018228-joomla-social-login-installation-configuration-and-troubleshooting" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_ONE'); ?></a></li>

		<li><a href="http://support.loginradius.com/customer/portal/articles/677100-how-to-get-loginradius-api-key-and-secret" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_TWO'); ?></a></li>

		<li><a href="http://support.loginradius.com/customer/portal/topics/272883-joomla-extension/articles" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_THREE'); ?></a></li>

		<li><a href="http://community.loginradius.com/" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_FOUR'); ?></a></li>

		<li><a href="https://www.loginradius.com/Loginradius/About" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_FIVE'); ?></a></li>

		<li><a href="https://www.loginradius.com/product/sociallogin" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_SIX'); ?></a></li>
		<li><a href="https://www.loginradius.com/loginradius-for-developers/loginRadius-cms" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_EIGHT'); ?></a></li>
		<li><a href="https://www.loginradius.com/loginradius-for-developers/loginradius-sdks" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_NINE'); ?></a></li>
		<li><a href="https://www.loginradius.com/loginradius/Testimonials" target="_blank"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_HELP_LINK_TEN'); ?></a></li>
</ul>
</div>
<div style="clear:both;"></div>
<div style="background:#EAF7FF; border: 1px solid #B3E2FF;  margin:0 0 10px 0; overflow:auto;">
	<h3 style="border-bottom:#000000 1px solid; margin:0px; padding:0 0 6px 0; border-bottom: 1px solid #B3E2FF; color: #000000; margin:10px;"><?php echo JText::_('COM_SOCIALLOGIN_STAY_UPDATE'); ?></h3>
	<p align="justify" style="line-height: 19px;font-size:12px !important;">
<?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_TECH_SUPPORT_TEXT_ONE'); ?> </p>
	<ul class="stay_ul">
  <li class="first">
    <iframe rel="tooltip" scrolling="no" frameborder="0" allowtransparency="true" style="border: none; overflow: hidden; width: 46px; height: 70px;" src="//www.facebook.com/plugins/like.php?app_id=194112853990900&amp;href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FLoginRadius%2F119745918110130&amp;send=false&amp;layout=box_count&amp;width=90&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font=arial&amp;height=90" data-original-title="Like us on Facebook"></iframe>
  </li>
</ul>
	<div>
</div>
	
</div>

<div style="clear:both;"></div>
 
<!-- Upgrade Box -->

<div style="background:#EAF7FF; border: 1px solid #B3E2FF; overflow:auto; margin:0 0 10px 0;">

<h3 style="border-bottom:#000000 1px solid; margin:0px; padding:0 0 6px 0; border-bottom: 1px solid #B3E2FF; color: #000000; margin:10px;"><?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT'); ?></h3>
<p align="justify" style="line-height: 19px; font-size:12px !important;">

<?php echo JText::_('COM_SOCIALLOGIN_EXTENSION_SUPPORT_TEXT'); ?> </p>

</div>

 </div>

	</div>

	<input type="hidden" name="task" value="" />

</form>