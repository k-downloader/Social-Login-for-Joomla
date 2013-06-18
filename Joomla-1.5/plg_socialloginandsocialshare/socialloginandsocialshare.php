<?php
defined ('_JEXEC') or die ('Restricted access');
jimport ('joomla.plugin.plugin');
jimport ('joomla.filesystem.file');
jimport ('joomla.user.helper');
jimport ('joomla.mail.helper' );
jimport ('joomla.application.component.helper');
jimport ('joomla.application.component.modelform');
jimport ('joomla.application.component.controller' );
jimport ('joomla.event.dispatcher');
jimport ('joomla.plugin.helper');
jimport ('joomla.utilities.date');

// Check if plugin is correctly installed.
if (!JFile::exists (dirname (__FILE__) . DS . 'LoginRadius.php') && !JFile::exists (dirname (__FILE__) . DS . 'socialloginandsocialshare_helper.php')) {
  JError::raiseNotice ('sociallogin_plugin', JText::_ ('COM_SOCIALLOGIN_PLUGIN_INSTALL_FAILURE'));
  return;
}

// Includes plugins required files.
require_once(dirname (__FILE__) . DS . 'socialloginandsocialshare_helper.php');
require_once(dirname (__FILE__) . DS . 'LoginRadius.php');

class plgSystemSocialLoginAndSocialShare extends JPlugin {
  
  function plgSystemSocialLoginAndSocialShare(&$subject, $config) {
    parent::__construct($subject,$config);
  }
  
  function onAfterInitialise() {
    $lrdata = array(); $id = ''; $email = ''; $user_id = ''; $msg = ''; $password = '';
	
	// Get module configration option value.
	$mainframe = JFactory::getApplication();
	$user = clone(JFactory::getUser());
	$db =& JFactory::getDBO();
	$config =& JFactory::getConfig();
	$session =& JFactory::getSession();
	$salt = JUserHelper::genRandomPassword(32);
    $crypt = JUserHelper::getCryptedPassword($password, $salt);
    $password = $crypt.':'.$salt;
    $language =& JFactory::getLanguage();
	$language->load('com_users');
	$language->load('com_socialloginandsocialshare', JPATH_ADMINISTRATOR);
	$authorize =& JFactory::getACL();
	$lr_settings = plgSystemSocialLoginTools::sociallogin_getsettings ();
	
	// Retrive data from LoginRadius.
	$obj = new LoginRadius();
	$lr_settings ['apisecret'] = (!empty($lr_settings ['apisecret']) ? $lr_settings ['apisecret'] : "");
    $userprofile = $obj->sociallogin_getapi($lr_settings ['apisecret']);
	
	// Checking user is logged in.
	if ($obj->IsAuthenticated == true && JFactory::getUser()->id) {
	  $lrdata = plgSystemSocialLoginTools::get_userprofile_data($userprofile);
	  
	  
	  $providerquery = "SELECT provider from #__LoginRadius_users WHERE provider=".$db->Quote ($lrdata['Provider'])." AND id = " . JFactory::getUser()->id;
	  $db->setQuery($providerquery);
	  $check_provider = $db->loadResult();
	  
	  // Check lr id exist.
	  $query = "SELECT LoginRadius_id from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . JFactory::getUser()->id;
      $db->setQuery($query);
      $check_id = $db->loadResult();
	  
	  // Try to map another account.
      if (empty($check_id)) {
		if(empty($check_provider)){
		$userImage = plgSystemSocialLoginTools::add_newid_image($lrdata);
		
		// Remove.
		$sql = "DELETE FROM #__LoginRadius_users WHERE LoginRadius_id = " . $db->Quote ($lrdata['id']);
		$db->setQuery ($sql);
		if ($db->query()) {
		
		// Add new id to db.
		$sql = "INSERT INTO #__LoginRadius_users SET id = " . JFactory::getUser()->id . ", LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']) . ", lr_picture = " . $db->Quote ($userImage);
		$db->setQuery ($sql);
		$db->query();
		}
		$mainframe->enqueueMessage (JText::_ ('COM_SOCIALLOGIN_ADD_ID'));
		$mainframe->redirect('index.php?option=com_socialloginandsocialshare&view=user&task=edit');
		}
		else 
		{
			JError::raiseWarning ('',JTEXT::_('COM_SOCIALLOGIN_EXIST_PROVIDER'));
			$mainframe->redirect('index.php?option=com_socialloginandsocialshare&view=user&task=edit');
			return false;
		}
		}
      else {
        JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_EXIST_ID'));
		$mainframe->redirect('index.php?option=com_socialloginandsocialshare&view=user&task=edit');
        return false;
      }
    }
	
	// User is not logged in trying to make log in user.
	if ($obj->IsAuthenticated == true && !JFactory::getUser()->id) {
	  
	  // Remove the session if any.
	  if ($session->get('tmpuser')) {
	    $session->clear('tmpuser');
	  }
	  
	  // Getting all user data.
	  $lrdata = plgSystemSocialLoginTools::get_userprofile_data($userprofile);
	  if ($lr_settings ['dummyemail'] == 0 && $lrdata['email'] == "") {
	    // Random email if not true required email.
		$lrdata['email'] = plgSystemSocialLoginTools::get_random_email($lrdata);
      }
	  
	  // Find the not activate user.

	    $query = "SELECT u.id FROM #__users AS u INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = '".$lrdata['id']."' AND u.activation != ''";
        $db->setQuery($query);
        $block_id = $db->loadResult();
        if (!empty($block_id) || $block_id) {
          JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_USER_NOTACTIVATE'));
          return false;
        }
		

	  // Find the block user.
       $query = "SELECT u.id FROM #__users AS u INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id WHERE lu.LoginRadius_id = '".$lrdata['id']."' AND u.block = 1";
       $db->setQuery($query);
       $block_id = $db->loadResult();
       if (!empty($block_id) || $block_id) {
         JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_USER_BLOCK'));
         return false;
       }

	  
	  // Checking user admin mail setting.
	  if ($lr_settings ['dummyemail'] == 1 && $lrdata['email'] == '') {
	    $usersConfig = JComponentHelper::getParams( 'com_users' );
	    $useractivation = $usersConfig->get( 'useractivation' );
		$query = "SELECT u.id FROM #__users AS u
     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id
     WHERE lu.LoginRadius_id = '".$lrdata['id']."' AND u.block = 0 AND u.activation = ''";
          $db->setQuery($query);
          $user_id = $db->loadResult();
		$newuser = true;
        if (isset($user_id)) {
		  $user =& JFactory::getUser($user_id);
            if ($user->id == $user_id) {
              $newuser = false;
            }
        }
        else {
		  if ($useractivation == '0') {
		    $lrdata['email'] = plgSystemSocialLoginTools::get_random_email($lrdata);
		  }
          else {
		    $title = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailtitle']);		      
			  $msg = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailrequiredmessage']);
		      $msgtype = 'msg';
		    // Register session variables.
            $session->set('tmpuser',$lrdata);
            plgSystemSocialLoginTools::enterEmailPopup($title, $msg, $msgtype);
		  }
         } 
	   }
	}
	
	// Check user click on enter mail popup submit button.
	if (isset($_POST['sociallogin_emailclick'])) {
	  $lrdata = $session->get('tmpuser');
	  if (isset($_POST['session']) && $_POST['session'] == $lrdata['session'] && !empty($lrdata['session'])) {
        $email = urldecode($_POST['email']);
        if(!JMailHelper::isEmailAddress($email)) {
		  $msgtype = 'warning';
			
			$title = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailtitle']);		      
			$msg = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailinvalidmessage']);
          plgSystemSocialLoginTools::enterEmailPopup($title, $msg, $msgtype);
		  return false;
        }
	    else {
		  $email = $db->getEscaped($email);
		  $query = "SELECT id FROM #__users WHERE email=".$db->Quote ($email);
		  $db->setQuery($query);
		  $user_exist = $db->loadResult();
	      if ($user_exist != 0 ) {
		    $msgtype = 'warning';
			  
			  $title = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailtitle']);		      
			  $msg = str_replace("%s",' <b> '.$lrdata['Provider'].' </b>',$lr_settings['emailinvalidmessage']);
            plgSystemSocialLoginTools::enterEmailPopup($title, $msg, $msgtype);
		    return false;
		  }
          else {
	        $lrdata = $session->get('tmpuser');
            $email = $db->getEscaped(urldecode($_POST['email']));
            $lrdata['email'] = $email;
	      }
	    }
	  }
	  else {
	    $session->clear('tmpuser');
	    JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_SESSION_EXPIRED'));
		return false;
	  }
    }
	
	// Checking user click on popup cancel button.
    else if (isset($_POST['cancel'])) {
	   // Redirect after Cancel click.
	    $session->clear('tmpuser');
	    $redirct = JURI::base();
	    $mainframe->redirect($redirct);
	}
	if (isset($lrdata['id']) && !empty($lrdata['id']) && !empty($lrdata['email'])) {
      
	  // Filter username form data.
	  if (!empty($lrdata['fname']) && !empty($lrdata['lname'])) {
	    $username = $lrdata['fname'].$lrdata['lname'];
	    $name = $lrdata['fname'];
	  }
	  else {
	    $username = plgSystemSocialLoginTools::get_filter_username($lrdata);
	    $name = plgSystemSocialLoginTools::get_filter_username($lrdata);
	  }	  
	  $query="SELECT u.id FROM #__users AS u
     INNER JOIN #__LoginRadius_users AS lu ON lu.id = u.id
     WHERE lu.LoginRadius_id = '". $lrdata['id'] ."' AND u.block = 0 AND u.activation = ''";
      $db->setQuery($query);
      $user_id = $db->loadResult();
      
	  // If not then check for email exist.
	  if (empty($user_id)) {
        $query = "SELECT id FROM #__users WHERE email='".$lrdata['email']."' AND block = 0 AND activation = '' ";
        $db->setQuery($query);
        $user_id = $db->loadResult();
		if (!empty($user_id)) {
		  $query = "SELECT LoginRadius_id from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . $user_id;
          $db->setQuery($query);
          $check_id = $db->loadResult();
	      if (empty($check_id) && $lr_settings ['linkaccount'] == '1') {
		    // Add new id to db.
		    $userImage = plgSystemSocialLoginTools::add_newid_image($lrdata);
		    $sql = "INSERT INTO #__LoginRadius_users SET id = " . $user_id . ", LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']) . ", lr_picture = " . $db->Quote ($userImage);
            $db->setQuery ($sql);
	        $db->query();
          }
		}
	  }
	 
      $newuser = true;
      if (isset($user_id)) {
	    $user =& JFactory::getUser($user_id);
        if ($user->id == $user_id) {
          $newuser = false;
        }
	  }
	  if ($newuser == true) {
	    $need_verification = false;
	    // If user registration is not allowed, show 403 not authorized.
	    $usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration') == '0') {
			JError::raiseNotice( '', JText::_( 'COM_SOCIALLOGIN_REGISTER_DISABLED' ));
			return;
		}
		// Initialize new usertype setting
		$newUsertype = $usersConfig->get( 'new_usertype');
		if (!$newUsertype) {
			$newUsertype = 'Registered';
		}

        // if username already exists
        $username = plgSystemSocialLoginTools::get_exist_username($username);
		
		// Remove special char if have.
		$username = plgSystemSocialLoginTools::remove_unescapedChar($username);
	    $name = plgSystemSocialLoginTools::remove_unescapedChar($name);
		
		// Insert data. 
		jimport ('joomla.user.helper');
	    $userdata = array ();
	    $userdata ['name'] = $db->getEscaped($name);
        $userdata ['username'] = $db->getEscaped($username);
        $userdata ['email'] = $db->getEscaped($lrdata['email']);
        $userdata ['usertype'] = $newUsertype;
        $userdata ['gid'] = $authorize->get_group_id('',$newUsertype, 'ARO');
        $userdata ['registerDate'] = JFactory::getDate ()->toMySQL ();
        $userdata ['password'] = JUserHelper::genRandomPassword();
        $userdata ['password2'] = $userdata ['password'];
		if (isset($_POST['sociallogin_emailclick'])) {
            $need_verification = true;
		}
		
		// If user activation is turned on, we need to set the activation information
		$useractivation = $usersConfig->get( 'useractivation' );
		if ($useractivation == '1' AND $need_verification == true)
		{
			jimport('joomla.user.helper');
			$user->set('activation', JUtility::getHash( JUserHelper::genRandomPassword()) );
			$user->set('block', '1');
		}
		else {
          $userdata ['activation'] = '';
          $userdata ['block'] = 0;
		}
		if (!$user->bind ($userdata)) {
          JError::raiseWarning ('', JText::_ ('COM_USERS_REGISTRATION_BIND_FAILED'));
          return false;
        }
        //Save the user
        if (!$user->save()) {
          JError::raiseWarning ('', JText::_ ('COM_SOCIALLOGIN_REGISTER_FAILED'));
          return false;
        }
		$usermessgae = 0;
	    $this->_sendMail($user, $password, $usermessgae);
        $user_id = $user->get ('id');
	    
		// Trying to insert image.
				$profile_Image = $lrdata['thumbnail'];
				if (empty($profile_Image)) {
				  $profile_Image = JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';
				}
		        $userImage = $username . $user_id . '.jpg';
				$sociallogin_savepath = JPATH_ROOT.DS.'images'.DS.'sociallogin'.DS;
				plgSystemSocialLoginTools::insert_user_picture($sociallogin_savepath, $profile_Image, $userImage);
				
				// Remove.
		        $sql = "DELETE FROM #__LoginRadius_users WHERE LoginRadius_id = " . $db->Quote ($lrdata['id']);
		        $db->setQuery ($sql);
		        if ($db->query ()) {
				  
				  //Add new id to db
		          $sql = "INSERT INTO #__LoginRadius_users SET id = " . $db->quote ($user_id) . ",  LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']).", lr_picture = " . $db->Quote ($userImage);
                  $db->setQuery ($sql);
	              $db->query();
			    }
		
		
		 // check for the community builder works.
          $query = "SHOW TABLES LIKE '%__comprofiler'";
          $db->setQuery($query);
          $cbtableexists = $db->loadResult();
          if (isset($cbtableexists)) {
		    plgSystemSocialLoginTools::make_cb_user($user_id, $profile_Image, $userImage, $lrdata);
          }
		  
		  // Check for kunena profile.
          if (JPluginHelper::isEnabled('system', 'kunena')) {
            plgSystemSocialLoginTools::check_exist_comkunena($user_id, $username, $profile_Image, $userImage, $lrdata);
          }
		  
		  // check for the k2 works.
          if (JPluginHelper::isEnabled('system', 'k2')) {
		    plgSystemSocialLoginTools::check_exist_comk2($user_id, $username, $profile_Image, $userImage, $lrdata);
		  }
		  // check for the jom social works.
          $query = "SHOW TABLES LIKE '%__community_users'";
          $db->setQuery($query);
          $jomtableexists = $db->loadResult();
          if (isset($jomtableexists)) {
		    plgSystemSocialLoginTools::make_jomsocial_user($user, $profile_Image, $userImage);
          }
		  if ($useractivation == '1' AND $need_verification == true) {
		    $usermessgae = 1;
            $this->_sendMail($user, $password, $usermessgae);
			$mainframe->enqueueMessage(JText::_ ('COM_PLG_REG_COMPLETE_ACTIVATE'));
			$session->clear('tmpuser');
            return false;
          }
		}
	  //updata user profile data on login the user
	  else if($newuser == false && $lr_settings ['updateuserdata'] == 1){
	    $need_verification = false;
	    // If user registration is not allowed, show 403 not authorized.
	    $usersConfig = &JComponentHelper::getParams( 'com_users' );

		
        // if username already exists
        $username = plgSystemSocialLoginTools::get_exist_username($username);
		
		// Remove special char if have.
		$username = plgSystemSocialLoginTools::remove_unescapedChar($username);
	    $name = plgSystemSocialLoginTools::remove_unescapedChar($name);				
		$user = JUser::getInstance($user_id);
		  $user->name = $name;
		  //update the user
          if (!$user->save(true)) {
            return false;
          }
          $user_id = $user->get ('id');
	    
		// Trying to insert image.
				$profile_Image = $lrdata['thumbnail'];
				if (empty($profile_Image)) {
				  $profile_Image = JURI::root().'media' . DS . 'com_socialloginandsocialshare' . DS .'images' . DS . 'noimage.png';
				}
		        $userImage = $username . $user_id . '.jpg';
				$sociallogin_savepath = JPATH_ROOT.DS.'images'.DS.'sociallogin'.DS;
				plgSystemSocialLoginTools::insert_user_picture($sociallogin_savepath, $profile_Image, $userImage);
				
				// Remove.
		        $sql = "DELETE FROM #__LoginRadius_users WHERE LoginRadius_id = " . $db->Quote ($lrdata['id']);
		        $db->setQuery ($sql);
		        if ($db->query ()) {
				  
				  //Add new id to db
		          $sql = "INSERT INTO #__LoginRadius_users SET id = " . $db->quote ($user_id) . ",  LoginRadius_id = " . $db->Quote ($lrdata['id']).", provider = " . $db->Quote ($lrdata['Provider']).", lr_picture = " . $db->Quote ($userImage);
                  $db->setQuery ($sql);
	              $db->query();
			    }
		
		
		 // check for the community builder works.
          $query = "SHOW TABLES LIKE '%__comprofiler'";
          $db->setQuery($query);
          $cbtableexists = $db->loadResult();
          if (isset($cbtableexists)) {
			plgSystemSocialLoginTools::make_cb_user($user_id, $profile_Image, $userImage, $lrdata);
          }
		  
		  // Check for kunena profile.
          if (JPluginHelper::isEnabled('system', 'kunena')) {
            plgSystemSocialLoginTools::check_exist_comkunena($user_id, $username, $profile_Image, $userImage, $lrdata);
          }
		  
		  // check for the k2 works.
          if (JPluginHelper::isEnabled('system', 'k2')) {
		    plgSystemSocialLoginTools::check_exist_comk2($user_id, $username, $profile_Image, $userImage, $lrdata);
		  }
		  // check for the jom social works.
          $query = "SHOW TABLES LIKE '%__community_users'";
          $db->setQuery($query);
          $jomtableexists = $db->loadResult();
          if (isset($jomtableexists)) {
			plgSystemSocialLoginTools::make_jomsocial_user($user_id, $profile_Image, $userImage);
          }	
	}
	  } 
	  if ($user_id) {
	    
	    $user =& JUser::getInstance((int)$user_id);
		 // Getting the ACL object
        $acl =& JFactory::getACL();
        // Getting user group from the ACL
        if ($user->get('tmp_user') == 1) {
            $grp = new JObject;
            $grp->set('name', 'Registered');
         } else {
            $grp = $acl->getAroGroup($user->get('id'));
         }
        // set user logged in
         $user->set('aid', 1);
        // All users types into the all special access group
         if($acl->is_group_child_of($grp->name, 'Registered') || $acl->is_group_child_of($grp->name, 'Public Backend')) {
              $user->set('aid', 2);
         }
        //Setting usertype based on the ACL group name
         $user->set('usertype', $grp->name);
        // Register session variables
        $session =& JFactory::getSession();
		$query = "SELECT lr_picture from #__LoginRadius_users WHERE LoginRadius_id=".$db->Quote ($lrdata['id'])." AND id = " . $user->get('id');
        $db->setQuery($query);
        $check_picture = $db->loadResult();
		$session->set('user_picture',$check_picture);
		$session->set('user_lrid',$lrdata['id']);

        $session->set('user',$user);
        // Getting the session object
        $table = & JTable::getInstance('session');
        $table->load( $session->getId());
        $table->guest = '0';
        $table->username  = $user->get('username');
        $table->userid = intval($user->get('id'));
        $table->usertype = $user->get('usertype');
        $table->gid  = $user->get('gid');
        $table->update();
        $user->setLastVisit();
	    $user =& JFactory::getUser();
		//Redirect after Login
	    $redirct = plgSystemSocialLoginTools::getReturnURL();
	    $mainframe->redirect($redirct);
		$session->clear('tmpuser');
	  }
    }

/*
 * Function that sends a verification link to exist user.
 */
   function _sendMail(&$user, $password, $usermessgae)
	{
		global $mainframe;
		$lr_settings = plgSystemSocialLoginTools::sociallogin_getsettings ();
        $db	=& JFactory::getDBO();
        $name = $user->get('name');
		$email = $user->get('email');
		$username = $user->get('username');
        $usersConfig = &JComponentHelper::getParams( 'com_users' );
		$sitename = $mainframe->getCfg( 'sitename' );
		$useractivation = $usersConfig->get( 'useractivation' );
		$mailfrom = $mainframe->getCfg( 'mailfrom' );
		$fromname = $mainframe->getCfg( 'fromname' );
		$data = $user->getProperties();
		$siteURL = JURI::base();
        $subject = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
		$subject = html_entity_decode($subject, ENT_QUOTES);

		if ( $usermessgae == 1 ){
			$message = sprintf ( JText::_( 'COM_PLG_SEND_MSG_ACTIVATE' ), $name, $sitename, $siteURL."index.php?option=com_user&task=activate&activation=".$user->get('activation'), $siteURL, $username, $data['password_clear']);
		} else {
			$message = sprintf ( JText::_( 'COM_PLG_SEND_MSG' ), $name, $sitename, $siteURL, $username, $data['password_clear']);
		}

		$message = html_entity_decode($message, ENT_QUOTES);
        JUtility::sendMail($mailfrom, $fromname, $email, $subject, $message);
		

		
        if ( $usermessgae == 0 && $lr_settings['sendemail'] == 1 ) {
		
		//get all super administrator
		$query = 'SELECT name, email, sendEmail' .
				' FROM #__users' .
				' WHERE LOWER( usertype ) = "super administrator"';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		// Send email to user
		if ( ! $mailfrom  || ! $fromname ) {
			$fromname = $rows[0]->name;
			$mailfrom = $rows[0]->email;
		}
		  // Send notification to all administrators
		  $subject2 = sprintf ( JText::_( 'Account details for' ), $name, $sitename);
		  $subject2 = html_entity_decode($subject2, ENT_QUOTES);

		  // get superadministrators id
		  foreach ( $rows as $row )
		  {
		   if ($row->sendEmail)
			 {
				$message2 = sprintf ( JText::_( 'COM_PLG_SEND_MSG_ADMIN' ), $row->name, $sitename, $name, $email, $username);
				$message2 = html_entity_decode($message2, ENT_QUOTES);
				JUtility::sendMail($mailfrom, $fromname, $row->email, $subject2, $message2);
			 }
		  }
	    }
	}

 }