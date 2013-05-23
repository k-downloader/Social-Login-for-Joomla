<?php
/* no direct access*/

defined( '_JEXEC' ) or die( 'Restricted access' );
if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php'); 

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

/**
 * plgContentSocialShare
 */  
class plgContentSocialShare  extends JPlugin {
  
   /**
    * Constructor
    * Loads the plugin settings and assigns them to class variables
    */
    public function __construct(&$subject)
    {
        parent::__construct($subject);
  
        // Loading plugin parameters
		$lr_settings = $this->sociallogin_getsettings();

        $this->_plugin = JPluginHelper::getPlugin('content', 'socialshare');
        $this->_params = new JRegistry($this->_plugin->params);
        
		//Properties holding plugin settings
		$this->rearrange_settings = (!empty($lr_settings['rearrange_settings']) ? unserialize($lr_settings['rearrange_settings']) : "");
		$this->share_articles = (!empty($lr_settings['s_articles']) ? unserialize($lr_settings['s_articles']) : "");
		$this->counter_articles = (!empty($lr_settings['c_articles']) ? unserialize($lr_settings['c_articles']) : "");
        $this->share_alignment = (!empty($lr_settings['sharealign']) ? $lr_settings['sharealign'] : "");
        $this->share_widget_position = (!empty($lr_settings['sharepos']) ? $lr_settings['sharepos'] : "");
		$this->counter_alignment = (!empty($lr_settings['counteralign']) ? $lr_settings['counteralign'] : "");
        $this->counter_widget_position = (!empty($lr_settings['counterpos']) ? $lr_settings['counterpos'] : "");
    }
     
	/**
	 * Before display content method
	 */
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0) {
		$beforediv ='';
		if(!($this->share_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->share_articles)) {
            foreach ($this->share_articles as $key=>$value) {
			  if ($article->id == $value) {
		       
			    $beforediv = "<DIV align='".$this->share_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showShare()."</DIV>";
			  }
			}
		  }
		}
		if(!($this->counter_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->counter_articles)) {
            foreach ($this->counter_articles as $key=>$value) {
			  if ($article->id == $value) {
		   
			$beforediv.= "<DIV align='".$this->counter_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showCounter()."</DIV>";
			}
		  }
		}
	  }
	  return $beforediv;
	} 
	
	  
	/**
	 * After display content method
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {
		$afterdiv = '';
		if(($this->share_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->share_articles)) {
			foreach ($this->share_articles as $key=>$value) {
			  if ($article->id == $value) {
            
			$afterdiv = "<DIV align='".$this->share_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showShare()." </DIV>";
			  }
		    }
		  }
		}
		if(($this->counter_widget_position)) {
			$app = JFactory::getApplication();
			if (!empty($this->counter_articles)) {
			foreach ($this->counter_articles as $key=>$value) {
			  if ($article->id == $value) {
            
			$afterdiv .= "<DIV align='".$this->counter_alignment."' style='padding-bottom:10px;padding-top:10px;'>".$this->showCounter()." </DIV>";
			}
		  }
		}
      }
	  return $afterdiv;
	} 
	private function showShare() {
      $lr_settings = $this->sociallogin_getsettings();
	  $socialsharetitle = "";
      if ($lr_settings['enableshare'] == '1') {
        if ($lr_settings['chooseshare'] == '0') {
		  $size = '32';
          $interface = 'horizontal';
		  $socialsharetitle='<div style="margin:0;"><b>'.$lr_settings['socialsharetitle'].'</b></div>';
        }
        else if ($lr_settings['chooseshare'] == '1') {
          $size = '16';
          $interface = 'horizontal';
		  $socialsharetitle='<div style="margin:0;"><b>'.$lr_settings['socialsharetitle'].'</b></div>';
        }
        else if ($lr_settings['chooseshare'] == '2') {
          $size = '32';
          $interface = 'simpleimage';
		  $socialsharetitle='<div style="margin:0;"><b>'.$lr_settings['socialsharetitle'].'</b></div>';
        }
        else if ($lr_settings['chooseshare'] == '3') {
          $size = '16';
          $interface = 'simpleimage';
		  $socialsharetitle='<div style="margin:0;"><b>'.$lr_settings['socialsharetitle'].'</b></div>';
        }
        else if ($lr_settings['chooseshare'] == '4') {
          $size = '32';
          $interface = 'Simplefloat';
		  $socialsharetitle="";
        }
        else if ($lr_settings['chooseshare'] == '5') {
          $size = '16';
          $interface = 'Simplefloat';
		  $socialsharetitle="";
        }
        if ($lr_settings['choosesharepos'] == '0') {
          $vershretop = (is_numeric($lr_settings['verticalsharetopoffset'])? $lr_settings['verticalsharetopoffset'] : '0').'px';
          $vershreright = '';
          $vershrebottom = '';
          $vershreleft = '0px';
        }
        else if ($lr_settings['choosesharepos'] == '1') {
          $vershretop = (is_numeric($lr_settings['verticalsharetopoffset'])? $lr_settings['verticalsharetopoffset'] : '0').'px';
          $vershreright = '0px';
          $vershrebottom = '';
          $vershreleft = '';
        }
        else if ($lr_settings['choosesharepos'] == '2') {
          $vershretop = (is_numeric($lr_settings['verticalsharetopoffset'])? $lr_settings['verticalsharetopoffset'] : '0').'px';
          $vershreright = '';
          $vershrebottom = (is_numeric($lr_settings['verticalsharetopoffset']) ? '' : '0px');
          $vershreleft = '0px';
        }
        else if ($lr_settings['choosesharepos'] == '3') {
          $vershretop = (is_numeric($lr_settings['verticalsharetopoffset'])? $lr_settings['verticalsharetopoffset'] : '0').'px';
          $vershreright = '0px';
		  $vershrebottom = (is_numeric($lr_settings['verticalsharetopoffset']) ? '' : '0px');
		  $vershreleft = '';
		}
		else {
		  $vershretop = '';
		  $vershreright = '';
		  $vershrebottom = '';
		  $vershreleft = '';
		}
		$sharescript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js" id="lrsharescript"></script> <script type="text/javascript"> LoginRadius.util.ready(function () { $i = $SS.Interface.'.$interface.'; $SS.Providers.Top = [';
		$rearrnage_provider_list=$this->rearrange_settings;
		if(empty($rearrnage_provider_list))
		{		
			  $this->rearrange_settings[] = 'facebook';
			  $this->rearrange_settings[] = 'googleplus';
			  $this->rearrange_settings[] = 'twitter';
			  $this->rearrange_settings[] = 'linkedin';
			  $this->rearrange_settings[] = 'pinterest';					
		}
		foreach ($this->rearrange_settings as $key=>$value) { 
		  $sharescript .= '"' .$value .'",';
        }
		$sharescript .=']; $u = LoginRadius.user_settings; ';
		if(isset($lr_settings['apikey']) && $lr_settings['apikey'] != ""){
			$sharescript .= '$u.apikey = "' . $lr_settings['apikey'] . '"; ';
		}
		$sharescript .= '$i.size = '.$size.';$i.left = "'.$vershreleft.'"; $i.top = "'.$vershretop.'";$i.right = "'.$vershreright.'";$i.bottom = "'.$vershrebottom.'"; $i.show("lrsharecontainer"); }); </script>';
      }
      else {
        $sharescript = "";
      }
      $includeButtonScript = $socialsharetitle.$sharescript.'<div class="lrsharecontainer"></div>';
      return $includeButtonScript;
   }
	

	private function showCounter(){
		$lr_settings = $this->sociallogin_getsettings();
		$socialcountertitle='';
		if ($lr_settings['enablecounter'] == '1') {
          $enablefblike = ((!empty($lr_settings['enablefblike']) AND $lr_settings['enablefblike'] == 'on')  ? 'Facebook Like' : '');
          $enablefbrecommend = ((!empty($lr_settings['enablefbrecommend']) AND $lr_settings['enablefbrecommend'] == 'on')  ? 'Facebook Recommend' : '');
          $enablefbsend = ((!empty($lr_settings['enablefbsend']) AND $lr_settings['enablefbsend'] == 'on')  ? 'Facebook Send' : '');
          $enablegplusone = ((!empty($lr_settings['enablegplusone']) AND $lr_settings['enablegplusone'] == 'on')  ? 'Google+ +1' : '');
          $enablegshare = ((!empty($lr_settings['enablegshare']) AND $lr_settings['enablegshare'] == 'on')  ? 'Google+ Share' : '');
          $enablelinkedinshare = ((!empty($lr_settings['enablelinkedinshare']) AND $lr_settings['enablelinkedinshare'] == 'on')  ? 'LinkedIn Share' : '');
          $enabletweet = ((!empty($lr_settings['enabletweet']) AND $lr_settings['enabletweet'] == 'on')  ? 'Twitter Tweet' : '');
          $enablestbadge = ((!empty($lr_settings['enablestbadge']) AND $lr_settings['enablestbadge'] == 'on')  ? 'StumbleUpon Badge' : '');
          $enableredditshare = ((!empty($lr_settings['enableredditshare']) AND $lr_settings['enableredditshare'] == 'on')  ? 'Reddit' : '');
		  if ($lr_settings['choosecounter'] == '0') {
		    $ishorizontal = 'true';
			$counter_type = 'horizontal';
			$socialcountertitle='<div style="margin:0;"><b>'.$lr_settings['socialcountertitle'].'</b></div>';
		  }
		  else if ($lr_settings['choosecounter'] == '1') {
		    $ishorizontal = 'true';
			$counter_type = 'vertical';
			$socialcountertitle='<div style="margin:0;"><b>'.$lr_settings['socialcountertitle'].'</b></div>';
		  }
		  else if ($lr_settings['choosecounter'] == '2') {
		    $ishorizontal = 'false';
			$counter_type = 'horizontal';
		  }
		  else if ($lr_settings['choosecounter'] == '3') {
		    $ishorizontal = 'false';
			$counter_type = 'vertical';
		  }
		  if ($lr_settings['choosecounterpos'] == '0') {
          $vercounttop = (is_numeric($lr_settings['verticalcountertopoffset'])? $lr_settings['verticalcountertopoffset'] : '0').'px';
          $vercountright = '';
          $vercountbottom = '';
          $vercountleft = '0px';
        }
        else if ($lr_settings['choosecounterpos'] == '1') {
          $vercounttop = (is_numeric($lr_settings['verticalcountertopoffset'])? $lr_settings['verticalcountertopoffset'] : '0').'px';
          $vercountright = '0px';
          $vercountbottom = '';
          $vercountleft = '';
        }
        else if ($lr_settings['choosecounterpos'] == '2') {
          $vercounttop = (is_numeric($lr_settings['verticalcountertopoffset'])? $lr_settings['verticalcountertopoffset'] : '0').'px';
          $vercountright = '';
          $vercountbottom = (is_numeric($lr_settings['verticalcountertopoffset'])? '' : '0px');
          $vercountleft = '0px';
        }
        else if ($lr_settings['choosecounterpos'] == '3') {
          $vercounttop = (is_numeric($lr_settings['verticalcountertopoffset'])? $lr_settings['verticalcountertopoffset'] : '0').'px';
          $vercountright = '0px';
		  $vercountbottom = (is_numeric($lr_settings['verticalcountertopoffset'])? '' : '0px');
		  $vercountleft = '';
		}
		else {
		  $vercounttop = '';
		  $vercountright = '';
		  $vercountbottom = '';
		  $vercountleft = '';
		}
          $counterscript = '<script type="text/javascript">var islrsharing = true; var islrsocialcounter = true;</script> <script type="text/javascript" src="//share.loginradius.com/Content/js/LoginRadius.js"></script> <script type="text/javascript"> LoginRadius.util.ready(function () { $SC.Providers.Selected = ["'.$enablefbsend.'","'.$enablefblike.'","'.$enablelinkedinshare.'","'.$enablegplusone.'","'.$enabletweet.'","'.$enablefbrecommend.'","'.$enablestbadge.'","'.$enablegshare.'","'.$enableredditshare.'"]; $S = $SC.Interface.simple; $S.isHorizontal = '.$ishorizontal.'; $S.countertype = "'.$counter_type.'"; $S.left = "'.$vercountleft.'"; $S.top = "'.$vercounttop.'"; $S.right = "'.$vercountright.'"; $S.bottom = "'.$vercountbottom.'"; $S.show("lrcounter_simplebox"); }); </script>';
        }

        else {

		  $counterscript = "";
		}
		$includeButtonScript = $socialcountertitle.$counterscript.'<div class="lrcounter_simplebox"></div> ';

		return $includeButtonScript;

    }
/**
 * Get the databse settings.
 */
	private function sociallogin_getsettings () {
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
  