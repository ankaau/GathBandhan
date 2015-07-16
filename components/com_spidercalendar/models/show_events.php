<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
Defined ('_JEXEC')  or  die();

jimport( 'joomla.application.component.model' );

class spidercalendarModelShow_events extends JModelLegacy

{



function showNote(){
	$user =JFactory::getUser();
	$option= JRequest::getVar( 'option');
	$calendar= (int)JRequest::getVar( 'calendar');
	
	
	$mainframe = JFactory::getApplication();
	
    $db =JFactory::getDBO();
	
	$query = 'SELECT title'.' FROM #__spidercalendar_calendar WHERE id='. $db->escape($calendar)	;
	$db->setQuery( $query );
	$calendar_name = $db->loadResult();

	// get calendar id ?
	
	$filter_order= $mainframe->getUserStateFromRequest( $option.'filter_order','filter_order','id','cmd' );
	$filter_order_Dir= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir',	'filter_order_Dir','','word' );
	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state', 	'filter_state', '','word' );
	
	
	$search = $mainframe->getUserStateFromRequest( $option.'search','search','','string' );
	$search = JString::strtolower( $search );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit',  'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	
	$lists = array();
	$where = array();
$lists['search']= $mainframe->getUserStateFromRequest( $option.'search','search','','string' );
$lists['search']= JString::strtolower($lists['search']);
$lists['startdate']= JRequest::getVar('startdate', "");
$lists['enddate']= JRequest::getVar('enddate', "");
if ( $lists['search'] ) {
		$where[] = ' #__spidercalendar_event.title LIKE "%'.$db->escape($search).'%"';
	}	
 if($lists['startdate']!='')
$where[] ="  `date`>='".$lists['startdate']."' ";
  if($lists['enddate']!='')
$where[] ="  `date`<='".$lists['enddate']."' ";


$filter='';
$filter=( count( $where ) ? ' WHERE calendar='.$db->escape($calendar) .' AND userID='.$db->escape($user->id).' AND' . implode( ' AND ', $where ) : '' );
if(count( $where )==0)
{
$filter.=' WHERE calendar='.$db->escape($calendar) .' AND userID='.$db->escape($user->id).'';
}
	if ($filter_order == 'id'){
		$orderby 	= ' ORDER BY id '. $filter_order_Dir;
	} else {
		$orderby 	= ' ORDER BY '. 
         $filter_order .' '. $filter_order_Dir .', id';
	}	
	
	// get the total number of records
	$query = 'SELECT COUNT(*)'
	. ' FROM #__spidercalendar_event '
	. $filter
	;
	$db->setQuery( $query );
	$total = $db->loadResult();

	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	 
	//$orderby
   $query = "SELECT #__spidercalendar_event.*, #__spidercalendar_event_category.title as cattitle FROM #__spidercalendar_event LEFT JOIN #__spidercalendar_event_category ON #__spidercalendar_event.category=#__spidercalendar_event_category.id
	$filter  $orderby ";
	$db->setQuery( $query);
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	

	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;	

	// search filter	
        $lists['search']= $search;	
		$lists['calendar']= $calendar;	
		$lists['calendar_name']= $calendar_name;
    // display function
	
	

	

	
	return array($rows, $pageNav, $lists,$option);
}



}