<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
Defined ('_JEXEC')  or  die();

jimport( 'joomla.application.component.model' );

class spidercalendarModelEdit_event extends JModelLegacy

{

	function editNote()
{
	$db		=JFactory::getDBO();

	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	
	$id 	= $cid[0];

	$row =JTable::getInstance('spidercalendar_event', 'Table');
	// load the row from the db table

	$row->load( $id);
    $query = 'SELECT * FROM #__spidercalendar_event_category WHERE published="1" '	;
	$db->setQuery( $query );
	$categories = $db->loadObjectList();
	
	$calendar=(int)$row->calendar;
	$query = 'SELECT title'.' FROM #__spidercalendar_calendar WHERE id='. $db->escape($calendar)	;
	$db->setQuery( $query );
	$calendar_name = $db->loadResult();
	
	
	$lists = array();
	$lists['calendar'] = $calendar;
	$lists['calendar_name'] = $calendar_name;
	$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', $row->published,'Yes','No');
	
	 //monthly_list Select List
  $monthly_list = array(1=>'First',8=>'Second',15=>'Third',22=>'Fourth','last'=>'Last');
  $monthly_listOptions = array();
  foreach($monthly_list as $key=>$value) 
    	$monthly_listOptions[] = JHTML::_('select.option',$key, $value);
 //end
 
 //month_week Select List
  $month_week = array('Mon'=>'Monday','Tue'=>'Tuesday','Wed'=>'Wednesday','Thu'=>'Thursday','Fri'=>'Friday','Sat'=>'Saturday','Sun'=>'Sunday');
  $month_weekOptions = array();
  foreach($month_week as $key=>$value) 
    	$month_weekOptions[] = JHTML::_('select.option',$key, $value);
 //end
 
 //year_month Select List
  $year_month = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
  $year_monthOptions = array();
  foreach($year_month as $key=>$value) 
    	$year_monthOptions[] = JHTML::_('select.option',$key, $value);
 //end
	
	
	$lists['monthly_list'] = JHTML::_('select.genericlist', $monthly_listOptions, 'monthly_list', 'class="cal_input"', 'value', 'text', $row->monthly_list);
	$lists['month_week'] = JHTML::_('select.genericlist', $month_weekOptions, 'month_week', 'class="cal_input"', 'value', 'text', $row->month_week);
	$lists['year_month'] = JHTML::_('select.genericlist', $year_monthOptions, 'year_month', 'class="cal_input"', 'value', 'text', $row->year_month);

	// display function 
	return array($lists, $row,$categories);
}

}