<?php
defined ('_JEXEC') or die ('Restricted access');
jimport ('joomla.application.component.view');

/**
 * Class generate view.
 */
 
class SocialLoginAndSocialShareViewSocialLoginAndSocialShare extends JView
{
	public $settings;
	
	/**
	 * SocialLoginAndSocialShare - Display administration area
	 */
	public function display ($tpl = null)
	{
		$document = &JFactory::getDocument ();
		$document->addStyleSheet ('components/com_socialloginandsocialshare/assets/css/socialloginandsocialshare.css');
		$document->addScript ('components/com_socialloginandsocialshare/assets/jquery.js');
		$document->addScript ('components/com_socialloginandsocialshare/assets/checkapi.js');
		$document->addScript ('http://code.jquery.com/ui/1.10.0/jquery-ui.js');
		$document->addScript ('//share.loginradius.com/Content/js/LoginRadius.js');
		$document->addScriptDeclaration('jQuery(function(){jQuery("#horsortable").sortable({revert: true});});');
		$document->addScriptDeclaration('jQuery(function(){jQuery("#versortable").sortable({revert: true});});');
		$model = &$this->getModel ();
		$this->settings = $model->getSettings ();
     	$this->form = $this->get ('Form');
		$this->addToolbar ();
        parent::display ($tpl);
	}

	
	/**
	 * SocialLoginAndSocialShare - Add admin option on toolbar
	 */
	protected function addToolbar ()
	{
		JRequest::setVar ('hidemainmenu', false);
		JToolBarHelper::title (JText::_ ('Social Login And Social Share Configuration'), 'configuration.gif');
		JToolBarHelper::apply ('apply');
		JToolBarHelper::save('save');
		JToolBarHelper::cancel ('cancel');
	}
}