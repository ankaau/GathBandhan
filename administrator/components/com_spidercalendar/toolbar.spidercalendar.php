<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/



defined( '_JEXEC' ) or die( 'Restricted access' );





require_once( JApplicationHelper::getPath( 'toolbar_html' ) );





switch ( $task )


{
	case 'add_calendar'  :
	case 'edit_calendar'  :	
		TOOLBAR_spidercalendar::_NEW_calendar();
		break;
		
	case 'add_theme'  :
	case 'edit_theme'  :	
		TOOLBAR_spidercalendar::_NEW_theme();
		break;	
	
	case 'theme'  :	
		TOOLBAR_spidercalendar::_DEFAULT_theme();
		break;

	case 'add_event'  :
	case 'edit_event'  :	
		TOOLBAR_spidercalendar::_NEW_event();
		break;
		
	case 'event'  :	
	case 'event_save_show'  :	
		TOOLBAR_spidercalendar::_DEFAULT_event();
		break;

	default:
		TOOLBAR_spidercalendar::_DEFAULT_calendar();
		break;
}


?>