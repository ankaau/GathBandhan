<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
Defined ('_JEXEC')  or  die();

jimport( 'joomla.application.component.model' );

class spidercalendarModelajaxbigcalendarday extends JModelLegacy

{

function getcategories()
{

$db =JFactory::getDBO();
	$query = "SELECT * FROM #__spidercalendar_event_category WHERE published='1'";

	$db->setQuery( $query );
	$categories = $db->loadObjectList();

	
	return array ($categories);

}


	function getDate()
	{
	
$app = JFactory::getApplication('site');
$componentParams = $app->getParams('com_spidercalendar');

$calendar_id = $componentParams->get('calendar');
	
	$calendar 	=JTable::getInstance('spidercalendar_calendar', 'Table');
			// load the row from the db table
$calendar->load( $calendar_id);

if((int)$calendar->def_month<10)
$month='0'.(int)$calendar->def_month;
else
$month=(int)$calendar->def_month;
	

		
	if($calendar->def_year!='' and $calendar->def_month!='' )
	$date=JRequest::getVar('date',$calendar->def_year.'-'.$month);
	else
	$date=JRequest::getVar('date',date("Y").'-'.date("m"));
	
		return array($date);
	}

	


	}
	

