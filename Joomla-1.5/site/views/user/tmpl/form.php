<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>
<?php $session =& JFactory::getSession();
      $lr_settings = array ();
      $db = JFactory::getDBO ();
	  $sql = "SELECT * FROM #__LoginRadius_settings";
      $db->setQuery ($sql);
      $rows = $db->LoadAssocList ();
      if (is_array ($rows)) {
        foreach ($rows AS $key => $data) {
          $lr_settings [$data ['setting']] = $data ['value'];
        }
      }
	  $sql = "SELECT * FROM #__LoginRadius_users WHERE id =".JFactory::getUser()->id;
      $db->setQuery ($sql);
      $acmaprows = $db->loadObjectList();
	  
	  ?>
	  <style>
	  .buttondelete {
	     background: -moz-linear-gradient(center top , #FCFCFC 0%, #E0E0E0 100%) repeat scroll 0 0 transparent;
         border: 1px solid #CCCCCC;
         border-radius: 5px 5px 5px 5px;
         color: #666666;
         padding: 1px;
         text-shadow: 0 1px 0 #FFFFFF;
		 cursor:pointer;
		 margin-left:5px;
      }
	  .AccountSetting-addprovider {
         list-style: none outside none !important;
         margin: 0 !important;
         padding: 0 !important;
         text-decoration: none;
		 line-height:normal!important;
      }
	  .AccountSetting-addprovider li {
         float: left !important;
         list-style: none outside none!important;
         min-width: 30px!important;
         word-wrap: break-word!important;
		 margin-bottom:5px !important;
       }
	  </style>
	  <?php
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='Off' && !empty($_SERVER['HTTPS']))
{
	$http='https://';
}
else
{
	$http='http://';
}
$loc = (isset($_SERVER['REQUEST_URI']) ? urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']) : urlencode($http.$_SERVER["HTTP_HOST"].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']));  
?>
<script src="//hub.loginradius.com/include/js/LoginRadius.js" ></script> <script type="text/javascript"> var options={}; options.login=true; LoginRadius_SocialLogin.util.ready(function () { $ui = LoginRadius_SocialLogin.lr_login_settings;$ui.interfacesize = "";$ui.apikey = "<?php echo $lr_settings['apikey'] ?>";$ui.callback="<?php echo $loc; ?>"; $ui.lrinterfacecontainer ="interfacecontainerdiv"; LoginRadius_SocialLogin.init(options); }); </script>

<fieldset id="users-profile-core" style=" width:580px;background: none repeat scroll 0 0 #F7FAFE;border: 1px solid #DDDDDD;">
	<legend style="color: #135CAE;">
		<?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_HEAD'); ?>
	</legend>
	    <div >
	       <div style="float:right;">
	         <?php if (!empty($lr_settings['apikey'])) {?>
              <br />
              <div id="interfacecontainerdiv" class="interfacecontainerdiv"> </div>
            <?php }?></div>
			<div style="float:left; width:290px;">
			   <div style="float:left; padding:5px;">
			   <?php $user_picture = $session->get('user_picture');?>
			   <img src="<?php if (!empty($user_picture)) { echo JURI::root().'images'.DS.'sociallogin'.DS. $session->get('user_picture');} else {echo JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';}?>" alt="<?php echo JFactory::getUser()->name?>" style="width:80px; height:auto;background: none repeat scroll 0 0 #FFFFFF; border: 1px solid #CCCCCC; display: block; margin: 2px 4px 4px 0; padding: 2px;">
			   </div>
			   <div style="float:right;padding:5px;font-size: 20px;margin: 5px;">
			   <b><?php echo JFactory::getUser()->name?></b>
			   </div>
			</div>
	      </div>
		  <div style="clear:both;"></div><br />
	  <?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_DESC'); ?><br /><br />
	  
	  <div style="width:350px;">
	  <ul class="AccountSetting-addprovider">
	  <?php $msg = JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG'); ?>
	  <?php foreach ($acmaprows as $row) {?>
	  
	<li>
	<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="userform" autocomplete="off" class="form-validate">
	<?php if ($row->LoginRadius_id == $session->get('user_lrid')) {
	        $msg = '<span style="color:red;">'.JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSGONE').'</span>';   
	      }
		  else {
		    $msg = JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_MSG');
		  }?>
		 
	        <span style="margin-right:5px;"> <img src="<?php echo 'administrator/components/com_socialloginandsocialshare/assets/img/'.$row->provider.'.png'; ?>" /></span>
			
			<?php echo $msg;?>
			<b><?php echo $row->provider; ?></b>
			<button type="submit" class="buttondelete" onclick="submitbutton( this.form );return false;"><span><?php echo JText::_('COM_SOCIALLOGIN_LINK_ACCOUNT_REMOVE'); ?></span></button>
	     <input type="hidden" name="id" value="<?php echo $this->user->get('id');?>" />
	     <input type="hidden" name="gid" value="<?php echo $this->user->get('gid');?>" />
	     <input type="hidden" name="option" value="com_socialloginandsocialshare" />
	     <input type="hidden" name="task" value="delmap" />
		 <input type="hidden" name="mapid" value="<?php echo $row->provider; ?>" />
		 <input type="hidden" name="lruser_id" value="<?php echo $row->LoginRadius_id; ?>" />
			</form>
			<?php echo JHtml::_('form.token'); ?></li><br />
	<?php }?>
	</ul>
	</div>
	
</fieldset>
<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="userform" autocomplete="off" class="form-validate">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	
	<fieldset id="users-profile-core" style=" width:580px;background: none repeat scroll 0 0 #F7FAFE;border: 1px solid #DDDDDD;">
	<legend style="color: #135CAE;">
	<?php echo $this->escape($this->params->get('page_title')); ?>
	</legend>
<?php endif; ?>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td>
		<label for="username">
			<?php echo JText::_( 'User Name' ); ?>:
		</label>
	</td>
	<td>
		<span><?php echo $this->user->get('username');?></span>
	</td>
</tr>
<tr>
	<td width="120">
		<label for="name">
			<?php echo JText::_( 'Your Name' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required" type="text" id="name" name="name" value="<?php echo $this->escape($this->user->get('name'));?>" size="40" />
	</td>
</tr>
<tr>
	<td>
		<label for="email">
			<?php echo JText::_( 'email' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox required validate-email" type="text" id="email" name="email" value="<?php echo $this->escape($this->user->get('email'));?>" size="40" />
	</td>
</tr>
<?php if($this->user->get('password')) : ?>
<tr>
	<td>
		<label for="password">
			<?php echo JText::_( 'Password' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox validate-password" type="password" id="password" name="password" value="" size="40" />
	</td>
</tr>
<tr>
	<td>
		<label for="password2">
			<?php echo JText::_( 'Verify Password' ); ?>:
		</label>
	</td>
	<td>
		<input class="inputbox validate-passverify" type="password" id="password2" name="password2" size="40" />
	</td>
</tr>
<?php endif; ?>
</table>
<?php if(isset($this->params)) :  echo $this->params->render( 'params' ); endif; ?>
	<button class="button validate" type="submit" onclick="submitbutton( this.form );return false;"><?php echo JText::_('Save'); ?></button>

	<input type="hidden" name="username" value="<?php echo $this->user->get('username');?>" />
	<input type="hidden" name="id" value="<?php echo $this->user->get('id');?>" />
	<input type="hidden" name="gid" value="<?php echo $this->user->get('gid');?>" />
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="save" />
	<?php echo JHTML::_( 'form.token' ); ?></fieldset>
</form>

