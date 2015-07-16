<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JPATH_COMPONENT.'/controller.php' );

$controller = JRequest::getVar( 'controller' );

JHTML::_('behavior.tooltip');
	
$classname    = 'spidercalendarController'.$controller;
$controller   = new $classname( );

$controller->execute( JRequest::getVar( 'task' ) );

$controller->redirect();

?>