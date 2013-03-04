<?php

/**

 * @version		$Id: helper.php 1.4 16 Team LoginRadius

 * @copyright	Copyright (C) 2011 - till Open Source Matters. All rights reserved.

 * @license		GNU/GPL

 * @license		GNU/GPL, see LICENSE.php

* Joomla! is free software. This version may have been modified pursuant

* to the GNU General Public License, and as distributed it includes or

* is derivative of works licensed under the GNU General Public License or

* other free or open source software licenses.

* See COPYRIGHT.php for copyright notices and details.

*/



// no direct access

defined('_JEXEC') or die('Restricted access');
class modSocialLoginAndSocialShareHelper
{
	function getReturnURL($params, $type)
	{
		if($itemid =  $params->get($type))
		{  
			$menu =& JSite::getMenu();  
			$item = $menu->getItem($itemid); //var_dump($menu);die;
			if ($item)
			{
				$url = JRoute::_($item->link.'&Itemid='.$itemid, false);
			}
			else
			{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
			}
				
		}
		else
		{
			// stay on the same page
			$uri = JFactory::getURI();
			$url = $uri->toString(array('path', 'query', 'fragment'));
		}

		return base64_encode($url);
	}
	function getType()
	{
		$user = & JFactory::getUser();
		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	function sociallogin_getsettings () {
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
      return $lr_settings;
    }

}

