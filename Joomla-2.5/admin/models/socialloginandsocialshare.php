<?php
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.modellist');

/**
 * SocialLoginAndSocialShare Model.
 */
 
class SocialLoginAndSocialShareModelSocialLoginAndSocialShare extends JModelList
{
	/**
	 * Save Settings.
	 */
	public function saveSettings ()
	{
		//Get database handle
		$db = $this->getDbo ();
        $mainframe = JFactory::getApplication();
		//Read Settings
		$horizontal_rearrange = JRequest::getVar ('horizontal_rearrange');
		$vertical_rearrange = JRequest::getVar ('vertical_rearrange');
		$horizontalcounter = JRequest::getVar ('horizontalcounter');
		$verticalcounter = JRequest::getVar ('verticalcounter');
		$settings = JRequest::getVar ('settings');
		$h_articles = JRequest::getVar ('h_articles');
		$v_articles = JRequest::getVar ('v_articles');
		$settings['apikey'] = trim($settings['apikey']);
		$settings['apisecret'] = trim($settings['apisecret']);
		$settings['h_articles'] = (sizeof($h_articles) > 0 ? serialize($h_articles) : "");
		$settings['v_articles'] = (sizeof($v_articles) > 0 ? serialize($v_articles) : "");
		$settings['horizontal_rearrange'] = (sizeof($horizontal_rearrange) > 0 ? serialize($horizontal_rearrange) : "");
		$settings['vertical_rearrange'] = (sizeof($vertical_rearrange) > 0 ? serialize($vertical_rearrange) : "");
		$settings['horizontalcounter'] = (sizeof($horizontalcounter) > 0 ? serialize($horizontalcounter) : "");
		$settings['verticalcounter'] = (sizeof($verticalcounter) > 0 ? serialize($verticalcounter) : "");
		$sql = "DELETE FROM #__LoginRadius_settings";
	    $db->setQuery ($sql);
	    $db->query ();
	  
	    //Insert new settings
	    foreach ($settings as $k => $v){
		  $sql = "INSERT INTO #__LoginRadius_settings ( setting, value )" . " VALUES ( " . $db->Quote ($k) . ", " . $db->Quote ($v) . " )";
		  $db->setQuery ($sql);
		  $db->query ();
	    }
	 }
	/**
	 * Read Settings
	 */
	public function getSettings ()
	{
		$settings = array ();
        $db = $this->getDbo ();
		$db->setQuery("CREATE TABLE IF NOT EXISTS #__loginradius_users (`id` int(11) DEFAULT NULL, `LoginRadius_id` varchar(255) DEFAULT NULL, `provider` varchar(255) DEFAULT NULL, `lr_picture` varchar(255) DEFAULT NULL) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
		$db->query ();
		$db->setQuery("CREATE TABLE IF NOT EXISTS #__loginradius_settings (
						`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
						`setting` varchar(255) NOT NULL,
						`value` varchar(1000) NOT NULL,
						PRIMARY KEY (`id`),
						UNIQUE KEY `setting` (`setting`)
						) ENGINE=MyISAM  DEFAULT CHARSET=utf8;");
		$db->query ();
		
        $sql = "SELECT * FROM #__LoginRadius_settings";
		$db->setQuery ($sql);
		$rows = $db->LoadAssocList ();

		if (is_array ($rows))
		{
			foreach ($rows AS $key => $data)
			{
				$settings [$data['setting']] = $data ['value'];
				
			}
		}

		return $settings;
	}
 }