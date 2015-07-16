<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_spidercalendar/tables');
JHTML::_('behavior.modal', 'a.modal'); 


class spidercalendarController extends JControllerLegacy
{


}
?>