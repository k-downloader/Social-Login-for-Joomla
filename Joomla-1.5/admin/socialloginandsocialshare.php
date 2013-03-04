<?php
defined ('_JEXEC') or die ('Direct Access to this location is not allowed.');
jimport ('joomla.application.component.controller');

require_once (JPATH_COMPONENT.DS.'controller.php');

$controller = new SocialLoginAndSocialShareController();

$controller->execute (JRequest::getCmd ('task', 'display'));

$controller->redirect ();
