<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once JPATH_COMPONENT . '/admin.spidercalendar.html.php';
require_once JPATH_COMPONENT . '/toolbar.spidercalendar.html.php';

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_spidercalendar/tables');

 $task	= JRequest::getCmd('task'); 
 $id = JRequest::getVar('id', 0, 'get', 'int');
 $user = JFactory::getUser();
if (!$user->authorise('core.manage', 'com_spidercalendar')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
JToolBarHelper::preferences('com_spidercalendar');	
	
 
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

	    
	case 'add_event_category'  :
	case 'edit_event_category'  :	
		TOOLBAR_spidercalendar::_NEW_event_category();
		break;	
	
	case 'event_category'  :	
		TOOLBAR_spidercalendar::_DEFAULT_event_category();
		break;	
			
				
	case 'plugin'  :	
		TOOLBAR_spidercalendar::_DEFAULT_plugin();
		break;
		
	default:
		TOOLBAR_spidercalendar::_DEFAULT_calendar();
		break;
}

 
 
 switch($task){
	case 'add_event':
		addNote();
		break;
		
	case 'cancel_event';		
		cancelNote();
		break;
	
	case 'apply_event':	
	case 'save_event';		
		saveNote();
		break;
	
		
	case 'edit_event':
    	editNote();
    	break;
		
	case 'event':
		showNote(JRequest::getVar('calendar', 0, '', 'int'));
	break;
		
	case 'event_save_show':
		showNote(save_before_show());
	break;
	
	case 'module_event':
		module_event();
	break;
		// This will work when you set task to 'remove
  	case 'remove_event':
   	 	removeNote();
  		break;
				
   case 'publish_event':
    	changeNote(1);
   		break;

   case 'unpublish_event':
    	changeNote(0);
    	break;				

////////////////CALENDAR////////////////////////////////////
	case 'add_calendar':
	case 'edit_calendar';
		edit_calendar();
		break;
	
	case 'save_calendar':
	case 'apply_calendar';
		save_calendar();
		break;
		
	case 'remove_calendar':
   	 	remove_calendar();
		remove_calendar_events();
  		break;
		
 	case 'publish_calendar':
   		change_calendar(1);
    	break;	 
			
	case 'unpublish_calendar':
	   	change_calendar(0);
	    break;	
	
////////////THEME////////////////
case 'add_theme':
case 'edit_theme';
		edit_theme();
		break;
case 'theme':
		show_theme();
		break;		

	case 'save_theme':
	case 'apply_theme';
		save_theme();
		break;	
	
	case 'cancel_theme';		
		cancel_theme();
		break;
	
	
	case 'remove_theme':
   	 	removeTheme();
  		break;
		
	case 'preview_theme':
   	    preview_theme();
  		break;
	
	case 'license':
	 license();
	 break;
	
	
		
	case 'plugin':
   	    plugin();
  		break;
	
	
	///////ev category

	case 'add_event_category':
	     edit_event_category();
		break;
		
	case 'edit_event_category':
		edit_event_category();
		break;
	
	case 'save_event_category':
	case 'apply_event_category';
		save_event_category();
		break;
		
	case 'remove_event_category':
   	 	remove_event_category();
		remove_event_category_events();
  		break;
		
 	case 'publish_event_category':
   		change_event_category(1);
    	break;	 
			
	case 'unpublish_event_category':
	   	change_event_category(0);
	    break;	
	
	case 'event_category':
	   	show_event_category();
	JSubMenuHelper::addEntry(JText::_('Calendar'), 'index.php?option=com_spidercalendar&task=calendar');
	JSubMenuHelper::addEntry(JText::_('Event Category'), 'index.php?option=com_spidercalendar&task=event_category', true );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_spidercalendar&task=theme' );		
	JSubMenuHelper::addEntry(JText::_('Plugin Code Generator'), 'index.php?option=com_spidercalendar&task=plugin');
	JSubMenuHelper::addEntry(JText::_('Licensing'), 'index.php?option=com_spidercalendar&task=license' );
	  
	  break;	

     	case 'cancel_event_category':
	   	cancel_event_category();
	    break;	
	

		
     case 'cancel_event_category':
	  cancel_event_category();
	 break;	
	////////////
	
	default:
		show_calendar();
		JSubMenuHelper::addEntry(JText::_('Calendar'), 'index.php?option=com_spidercalendar&task=calendar', true);
		JSubMenuHelper::addEntry(JText::_('Event Category'), 'index.php?option=com_spidercalendar&task=event_category' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_spidercalendar&task=theme' );		
	JSubMenuHelper::addEntry(JText::_('Plugin Code Generator'), 'index.php?option=com_spidercalendar&task=plugin');
	JSubMenuHelper::addEntry(JText::_('Licensing'), 'index.php?option=com_spidercalendar&task=license' );

		break;

		
}
	if($task=='theme'){
	JSubMenuHelper::addEntry(JText::_('Calendar'), 'index.php?option=com_spidercalendar&task=calendar');
	JSubMenuHelper::addEntry(JText::_('Event Category'), 'index.php?option=com_spidercalendar&task=event_category' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_spidercalendar&task=theme', true );
	JSubMenuHelper::addEntry(JText::_('Plugin Code Generator'), 'index.php?option=com_spidercalendar&task=plugin');
	JSubMenuHelper::addEntry(JText::_('Licensing'), 'index.php?option=com_spidercalendar&task=license' );

	}
	
		if($task=='license'){
	JSubMenuHelper::addEntry(JText::_('Calendar'), 'index.php?option=com_spidercalendar&task=calendar');
	JSubMenuHelper::addEntry(JText::_('Event Category'), 'index.php?option=com_spidercalendar&task=event_category' );
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_spidercalendar&task=theme' );
	JSubMenuHelper::addEntry(JText::_('Plugin Code Generator'), 'index.php?option=com_spidercalendar&task=plugin');
	JSubMenuHelper::addEntry(JText::_('Licensing'), 'index.php?option=com_spidercalendar&task=license',true );
	}
	
	if($task=='plugin'){
	JSubMenuHelper::addEntry(JText::_('Calendar'), 'index.php?option=com_spidercalendar&task=calendar');
	JSubMenuHelper::addEntry(JText::_('Event Category'), 'index.php?option=com_spidercalendar&task=event_category' );	
	JSubMenuHelper::addEntry(JText::_('Themes'), 'index.php?option=com_spidercalendar&task=theme');
	JSubMenuHelper::addEntry(JText::_('Plugin Code Generator'), 'index.php?option=com_spidercalendar&task=plugin', true );
	JSubMenuHelper::addEntry(JText::_('Licensing'), 'index.php?option=com_spidercalendar&task=license' );

	}	
	
	
$exist="0";

    $db =JFactory::getDBO();
	$query = "SHOW columns FROM #__spidercalendar_theme";
	$db->setQuery($query);
	$fields = $db->loadObjectList();
	for($i=0; $i<count($fields); $i++){
	if($fields[$i]->Field=="date_bg_color" )
	$exist="1";

	
	}
	if($exist!="1")
	{
	$query = "ALTER TABLE #__spidercalendar_theme  ADD date_bg_color varchar(255), ADD event_bg_color1 varchar(255)
	, ADD event_bg_color2 varchar(255), ADD event_num_bg_color1 varchar(255), ADD event_num_bg_color2 varchar(255), ADD event_num_color varchar(255)
	, ADD date_font_size varchar(255), ADD event_num_font_size varchar(255), ADD event_table_height varchar(255), ADD date_height varchar(255)
	, ADD ev_title_bg_color varchar(255), ADD week_font_size varchar(255), ADD day_month_font_size varchar(255), ADD week_font_color varchar(255), ADD day_month_font_color varchar(255)
	, ADD views_tabs_bg_color varchar(255), ADD views_tabs_text_color varchar(255), ADD views_tabs_font_size varchar(255)";
	$db->setQuery($query);
	$db->Query($query);
	}

	
	$query="CREATE TABLE IF NOT EXISTS `#__spidercalendar_event_category`(
`id` int(11) NOT NULL AUTO_INCREMENT,
`title` varchar(255) NOT NULL,
`published` int(11) NOT NULL,
`color` varchar(255) NOT NULL,
`calendar_id` int(11) NOT NULL,
`description` varchar(255)  NOT NULL,
 PRIMARY KEY (`id`)
)ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
	$db->setQuery($query);
	$db->Query($query);
	
	$exist="0";
$query = "SHOW columns FROM #__spidercalendar_event";
	$db->setQuery($query);
	$fields = $db->loadObjectList();
	for($i=0; $i<count($fields); $i++){
	if($fields[$i]->Field=="category" )
	$exist="1";

	
	}
	if($exist!="1")
	{
	$query = "ALTER TABLE #__spidercalendar_event  ADD category int(11)";
	$db->setQuery($query);
	$db->Query($query);
	}	
	
$exist="0";
$query = "SHOW columns FROM #__spidercalendar_calendar";
	$db->setQuery($query);
	$fields = $db->loadObjectList();
	for($i=0; $i<count($fields); $i++){
	if($fields[$i]->Field=="email" )
	$exist="1";

	
	}
	if($exist!="1")
	{
	$query = "ALTER TABLE #__spidercalendar_calendar  ADD email varchar(255), ADD get_email int(11)";
	$db->setQuery($query);
	$db->Query($query);
	}		
	

	
function license()

{

?>
<p style="font-size: 13px;">This component is the non-commercial version of the Spider Event Calendar. Use of the calendar is free.
The only limitation is the use of the themes. If you want to use one of the 11 standard themes or create a new one that satisfies the needs of your web site, you are required to purchase a license.
Purchasing a license will add 11 standard themes and give possibility to edit the themes of the Spider Event Calendar.</p>


<a href="http://web-dorado.com/products/joomla-calendar.html" target="_blank" > 

<input style="width: 125px;height: 29px;"  type="button" value="Purchase a License">
</a>
<?php


}

	

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// T H E M E ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function show_event_category()
{

$option	= JRequest::getVar('option'); 
	$mainframe = JFactory::getApplication();;
    $db =JFactory::getDBO();
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order_event_category', 'filter_order_event_category','id','cmd' );
	//$filter_order='id';
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir_event_category', 'filter_order_Dir_event_category','desc','word' );
	$search_event_category = $mainframe-> getUserStateFromRequest( $option.'search_event_category', 'search_event_category','','string' );
	$search_event_category = JString::strtolower( $search_event_category );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	$where = array();
	
	if ( $search_event_category ) {
		$where[] = 'title LIKE "%'.$db->escape($search_event_category).'%"';
	}	
	
	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	if ($filter_order == 'id'){
		$orderby 	= ' ORDER BY id '.$filter_order_Dir;
	} else {
		$orderby 	= ' ORDER BY '. 
         $filter_order .' '. $filter_order_Dir .', id';
	}	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__spidercalendar_event_category". $where;

	$db->setQuery( $query );
	$total = $db->loadResult();
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	
	$query = "SELECT * FROM #__spidercalendar_event_category". $where. $orderby;
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	
	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;
		
	// search_calendar filter	
        $lists['search_event_category']= $search_event_category;	
	
    // display function
	
	
	HTML_contact::show_event_category($rows, $pageNav, $lists);
}	

function  edit_event_category()
{

	$db		=JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));

	$id 	= $cid[0];
	$row =JTable::getInstance('spidercalendar_event_category', 'Table');
	// load the row from the db table
	$row->load($id);
	
	$lists = array();
	if($row->published=='')
	$row->published=1;
	$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', $row->published);
	
	

	// display function 
	HTML_contact::edit_event_category($lists, $row);

}
 
 
 function  save_event_category()
{
  	$mainframe = JFactory::getApplication();
	$row =JTable::getInstance('spidercalendar_event_category', 'Table');
		$row->description =JRequest::getVar( 'description', '','post', 'string', JREQUEST_ALLOWRAW);
		$row->title = JRequest::getVar( 'title', '','post', 'string', JREQUEST_ALLOWRAW );

	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	$task		= JRequest::getCmd( 'task' );
	


	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	switch ($task)
		{
			case 'apply_event_category' :
				$msg = JText::sprintf('Changes to category saved');
				$mainframe->redirect('index.php?option=com_spidercalendar&task=edit_event_category&cid[]='.$row->id, $msg,'message');
				break;

			case 'save_event_category' :
			
				$msg = JText::sprintf('Category Saved');
				$mainframe->redirect('index.php?option=com_spidercalendar&task=event_category', $msg,'message');
				break;
			
		}
	
	
	
	$mainframe->redirect($link, $msg,'message');




}

function  remove_event_category()
{

  $mainframe = JFactory::getApplication();;
  // Initialize variables	
  $db =JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);
  
  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement

    $query = 'DELETE FROM #__spidercalendar_event_category'.' WHERE id IN ( '. $cids .' )';
    // Execute query
    
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
	  
    }
	
	 $query1 = 'UPDATE  #__spidercalendar_event SET category="0" WHERE category IN ( '. $cids .' )';
    // Execute query
    
    // Execute query
    $db->setQuery( $query1 );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
	  
    }
	
  }
  

  // After all, redirect again to frontpage
  $mainframe->redirect( "index.php?option=com_spidercalendar&task=event_category",'','message');


}



function  change_event_category($state=0)
{
 $mainframe = JFactory::getApplication();;
  // Initialize variables
  $db 	=JFactory::getDBO();
  // define variable $cid from GET
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );	
  JArrayHelper::toInteger($cid);
  // Check there is/are item that will be changed. 
  //If not, show the error.
  if (count( $cid ) < 1) {
    $action = $state ? 'publish_event_category' : 'unpublish_event_category';
    JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
  }
  // Prepare sql statement, if cid more than one, 
  // it will be "cid1, cid2, cid3, ..."
  $cids = implode( ',', $cid );
  $query = 'UPDATE #__spidercalendar_event_category' . ' SET published = '.(int) $state .' WHERE id IN ( '. $cids .' )'  ;
  // Execute query
  $db->setQuery( $query );
  if (!$db->query()) {
    JError::raiseError(500, $db->getErrorMsg() );
  }
  if (count( $cid ) == 1) {
    $row =JTable::getInstance('spidercalendar_event_category', 'Table');
    $row->checkin( intval( $cid[0] ) );
  }
  // After all, redirect to front page
  $mainframe->redirect( 'index.php?option=com_spidercalendar&task=event_category','','message' );


}

function  cancel_event_category()
{

$mainframe = JFactory::getApplication();
 $mainframe->redirect( 'index.php?option=com_spidercalendar&task=event_category','','message' );
}	
	



//////////////////////////


function plugin()

{
	$db		=& JFactory::getDBO();

	
	
	
	$query = "SELECT id,title FROM #__spidercalendar_calendar";
	$db->setQuery($query);
	$calendars = $db->loadObjectList();


	HTML_contact::plugin($calendars);

}


function show_theme(){
	$option	= JRequest::getVar('option'); 
	$mainframe = JFactory::getApplication();
	
    $db =JFactory::getDBO();
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order_theme', 'filter_order_theme','id','cmd' );
	//$filter_order='id';
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir_theme', 'filter_order_Dir_theme','desc','word' );
	$search_theme = $mainframe-> getUserStateFromRequest( $option.'search_theme', 'search_theme','','string' );
	$search_theme = JString::strtolower( $search_theme );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	$where = array();
	
	if ( $search_theme ) {
		$where[] = 'title LIKE "%'.$db->escape($search_theme).'%"';
	}	
	
	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	if ($filter_order == 'id'){
		$orderby 	= ' ORDER BY id '.$filter_order_Dir;
	} else {
		$orderby 	= ' ORDER BY '. 
         $filter_order .' '. $filter_order_Dir .', id';
	}	
	
		
	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__spidercalendar_theme". $where;

	$db->setQuery( $query );
	$total = $db->loadResult();
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	
	$query = "SELECT * FROM #__spidercalendar_theme". $where. $orderby;
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	
	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;
		
	// search_calendar filter	
        $lists['search_theme']= $search_theme;	
	
    // display function
	
	
	
	HTML_contact::show_theme($rows, $pageNav, $lists);
}


function edit_theme(){	
	$db		=JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));

	$id 	= $cid[0];
	$row =JTable::getInstance('spidercalendar_theme', 'Table');
	// load the row from the db table
	$row->load( $id);
$lists = array();


	 //title_font Select List
  $title_font = array(''=>'- Select Font -','Arial'=>'Arial','Courier New'=>'Courier New','Georgia'=>'Georgia','Tahoma'=>'Tahoma','Verdana'=>'Verdana','Impact'=>'Impact');
  $title_fontOptions = array();
  foreach($title_font as $key=>$value) 
    	$title_fontOptions[] = JHTML::_('select.option',$key, $value);
 //end
  
  
  //title_style Select List
  $title_style = array('normal'=>'Normal','bold'=>'Bold','italic'=>'Italic','bold/italic'=>'Bold and Italic');
  $title_styleOptions = array();
  foreach($title_style as $key=>$value) 
    	$title_styleOptions[] = JHTML::_('select.option',$key, $value);
 //end
 
 
 
 
 
 //date_font Select List
  $date_font = array(''=>'- Select Font -','Arial'=>'Arial','Courier New'=>'Courier New','Georgia'=>'Georgia','Tahoma'=>'Tahoma','Verdana'=>'Verdana','Impact'=>'Impact');
  $date_fontOptions = array();
  foreach($date_font as $key=>$value) 
    	$date_fontOptions[] = JHTML::_('select.option',$key, $value);
 //end
  
  
  //date_style Select List
  $date_style = array('normal'=>'Normal','bold'=>'Bold','italic'=>'Italic','bold/italic'=>'Bold and Italic');
  $date_styleOptions = array();
  foreach($date_style as $key=>$value) 
    	$date_styleOptions[] = JHTML::_('select.option',$key, $value);
 //end

 //week_start_day Select List
  $week_start_day = array('mo'=>'Monday','su'=>'Sunday');
  $week_start_dayOptions = array();
  foreach($week_start_day as $key=>$value) 
    	$week_start_dayOptions[] = JHTML::_('select.option',$key, $value);
 //end
 
 //month_type Select List
  $month_type = array(1=>'Previous,current,next',2=>'Current');
  $month_typeOptions = array();
  foreach($month_type as $key=>$value) 
    	$month_typeOptions[] = JHTML::_('select.option',$key, $value);
 //end
 
	
	if($row->show_time!='')
	$lists['show_time'] = JHTML::_('select.booleanlist', 'show_time' , 'class="inputbox"',$row->show_time  );
	else
	$lists['show_time'] = JHTML::_('select.booleanlist', 'show_time' , 'class="inputbox"',1  );

	if($row->show_repeat!='')
	$lists['show_repeat'] = JHTML::_('select.booleanlist', 'show_repeat' , 'class="inputbox"',$row->show_repeat  );
	else
	$lists['show_repeat'] = JHTML::_('select.booleanlist', 'show_repeat' , 'class="inputbox"',1  );

	
	
	$lists['title_style'] = JHTML::_('select.genericlist', $title_styleOptions, 'title_style', 'class="inputbox"', 'value', 'text', $row->title_style);
	$lists['title_font'] = JHTML::_('select.genericlist', $title_fontOptions, 'title_font', 'class="inputbox"', 'value', 'text', $row->title_font);
	
	$lists['week_start_day'] = JHTML::_('select.genericlist', $week_start_dayOptions, 'week_start_day', 'class="inputbox"', 'value', 'text', $row->week_start_day);

	$lists['date_style'] = JHTML::_('select.genericlist', $date_styleOptions, 'date_style', 'class="inputbox"', 'value', 'text', $row->date_style);
	$lists['date_font'] = JHTML::_('select.genericlist', $date_fontOptions, 'date_font', 'class="inputbox"', 'value', 'text', $row->date_font);
	
	$lists['month_type'] = JHTML::_('select.genericlist', $month_typeOptions, 'month_type', 'class="inputbox"', 'value', 'text', $row->month_type);

	// display function 
	HTML_contact::edit_theme($row,$lists);
}

function save_theme(){
	$mainframe = JFactory::getApplication();;
	$row =JTable::getInstance('spidercalendar_theme', 'Table');
	

	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	$task		= JRequest::getCmd( 'task' );
	


	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	switch ($task)
		{
			case 'apply_theme' :
				$msg = JText::sprintf('Changes to calendar saved');
				$mainframe->redirect('index.php?option=com_spidercalendar&task=edit_theme&cid[]='.$row->id, $msg,'message');
				break;

			case 'save_theme' :
			
				$msg = JText::sprintf('calendar Saved');
				$mainframe->redirect('index.php?option=com_spidercalendar&task=theme', $msg,'message');
				break;
			
		}
	
	
	
	$mainframe->redirect($link, $msg,'message');
}

function cancel_theme(){
  $mainframe = JFactory::getApplication();;
  $mainframe->redirect( 'index.php?option=com_spidercalendar&task=theme','','message' );
}

function removeTheme()
{

  $mainframe = JFactory::getApplication();;
  // Initialize variables	
  $db =JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);

  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement
    $query = 'DELETE FROM #__spidercalendar_theme'
    . ' WHERE id IN ( '. $cids .' )'
    ;
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
    }
  }

  // After all, redirect again to frontpage
  $mainframe->redirect( "index.php?option=com_spidercalendar&task=theme",'','message');
}

function  preview_theme()
{

HTML_contact::preview_theme();

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// E V E N T ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function back(){
	$mainframe = JFactory::getApplication();;
	$link = 'index.php?option=com_spidercalendar';
	$mainframe->redirect($link,'','message');

}


function save_before_show(){
    $db =JFactory::getDBO();

	$row =JTable::getInstance('spidercalendar_calendar', 'Table');
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	return $row->id;
	
}



function saveNote(){
	$mainframe = JFactory::getApplication();;
	$date=JRequest::getVar( 'date');
	$date_end=JRequest::getVar( 'date_end');
	$row =JTable::getInstance('spidercalendar_event', 'Table');
	$task=JRequest::getCmd('task');
	$db =JFactory::getDBO();
	
	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	/*$query = "SELECT * from #__spidercalendar_event where date=".$db->escape($date)." and id<>".$db->escape(JRequest::getVar( 'id'));
	$db->setQuery( $query );
	$total = $db->loadResult();
	if(count($total)>0)
		$mainframe->redirect('index.php?option=com_spidercalendar&task=event', 'This record already exists');*/
	
	
	$select_from=JRequest::getVar( 'select_from');
	$select_to=JRequest::getVar( 'select_to');
	
	
	
	$row->date =$date;
	$row->date_end =JRequest::getVar( 'date_end');
	if(JRequest::getVar( 'selhour_from'))
	{
	if(JRequest::getVar( 'selhour_to'))
		$row->time = JRequest::getVar( 'selhour_from').':'.JRequest::getVar( 'selminute_from').' '.$select_from.' - '.JRequest::getVar( 'selhour_to').':'.JRequest::getVar( 'selminute_to').' '.$select_to;
	else
		$row->time = JRequest::getVar( 'selhour_from').':'.JRequest::getVar( 'selminute_from').' '.$select_from;
	}
	else
	$row->time ="";
	
	$row->text_for_date = JRequest::getVar( 'text_for_date', '','post', 'string', JREQUEST_ALLOWRAW );
	$row->title = JRequest::getVar( 'title', '','post', 'string', JREQUEST_ALLOWRAW );
	
	if($row->repeat_method=='no_repeat')
	$row->date_end=$row->date;
	
	
	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	
	switch ($task)
	{
		case 'apply_event':
			$msg ='Changes to event saved';		
			$link ='index.php?option=com_spidercalendar&task=edit_event&cid[]='.$row->id;
			break;
		
		case 'save_event':
		default:
			$msg = 'event Saved';
			$link = 'index.php?option=com_spidercalendar&task=event&calendar='.$row->calendar;
			break;
	}	
	
	$mainframe->redirect($link, $msg,'message');	
	
}

function addNote(){
	
	 $db =JFactory::getDBO();
	
	$calendar=JRequest::getVar('calendar', 0, '', 'int');
	$query = 'SELECT title'.' FROM #__spidercalendar_calendar WHERE id='.$db->escape($calendar)	;
	$db->setQuery( $query );
	$calendar_name = $db->loadResult();
	
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	
	$id 	= $cid[0];
		$row =JTable::getInstance('spidercalendar_event', 'Table');
	// load the row from the db table

	$row->load( $id);
	
    $query = 'SELECT * FROM #__spidercalendar_event_category WHERE published="1" '	;
	$db->setQuery( $query );
	$categories = $db->loadObjectList();

	$lists['calendar'] = $calendar;
	$lists['calendar_name'] = $calendar_name;
	
	$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', 1 );
	
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
	
	
	$lists['monthly_list'] = JHTML::_('select.genericlist', $monthly_listOptions, 'monthly_list', 'class="inputbox"', 'value', 'text');
	$lists['month_week'] = JHTML::_('select.genericlist', $month_weekOptions, 'month_week', 'class="inputbox"', 'value', 'text');
	$lists['year_month'] = JHTML::_('select.genericlist', $year_monthOptions, 'year_month', 'class="inputbox"', 'value', 'text');



// display function
	HTML_contact::addNote($lists, $categories, $row);
}

function showNote($calendar){
	$option= JRequest::getVar( 'option');
	$mainframe = JFactory::getApplication();;
	
    $db =JFactory::getDBO();
	
	$query = 'SELECT title'.' FROM #__spidercalendar_calendar WHERE id='.$db->escape($calendar)	;
	$db->setQuery( $query );
	$calendar_name = $db->loadResult();

	// get calendar id ↑
	
	$filter_order= $mainframe->getUserStateFromRequest( $option.'filter_order_note','filter_order_note','id','cmd' );
	$filter_order_Dir= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir_note',	'filter_order_Dir_note','','word' );
	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state_note', 	'filter_state_note', '','word' );
	
	
	$search_note = $mainframe->getUserStateFromRequest( $option.'search_note','search_note','','string' );
	$search_note = JString::strtolower( $search_note );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit',  'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	
	$lists = array();
	$where = array();
$lists['search_note']= $mainframe->getUserStateFromRequest( $option.'search_note','search_note','','string' );
$lists['search_note']= JString::strtolower($lists['search_note']);
$lists['startdate']= JRequest::getVar('startdate', "");
$lists['enddate']= JRequest::getVar('enddate', "");
if ( $lists['search_note'] ) {
		$where[] = ' #__spidercalendar_event.title LIKE "%'.$db->escape($search_note).'%"';
	}	
 if($lists['startdate']!='')
$where[] ="  `date`>='".$db->escape($lists['startdate'])."' ";
  if($lists['enddate']!='')
$where[] ="  `date`<='".$db->escape($lists['enddate'])."' ";

$filter='';
$filter=( count( $where ) ? 'WHERE' . implode( ' AND ', $where ) : '' );
if(count( $where )==0)
{
$filter.=' WHERE calendar='.$db->escape($calendar);
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
	
	
	$query = "SELECT #__spidercalendar_event.*, #__spidercalendar_event_category.title as cattitle FROM #__spidercalendar_event LEFT JOIN #__spidercalendar_event_category ON #__spidercalendar_event.category=#__spidercalendar_event_category.id  $filter  $orderby ";
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	
	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;	

	// search filter	
        $lists['search_note']= $search_note;	
		$lists['calendar']= $calendar;	
		$lists['calendar_name']= $calendar_name;
    // display function
	
	
	
	

	
	HTML_contact::showNote($rows, $pageNav, $lists,$option);
}

//edit note

function editNote()
{
	$db		=JFactory::getDBO();

	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));
	
	$id 	= $cid[0];

	$row =JTable::getInstance('spidercalendar_event', 'Table');
	// load the row from the db table

	$row->load( $id);

	
	
	$calendar=$row->calendar;
	$query = 'SELECT title'.' FROM #__spidercalendar_calendar WHERE id='.$db->escape($calendar);
	$db->setQuery( $query );
	$calendar_name = $db->loadResult();
	  $query = 'SELECT * FROM #__spidercalendar_event_category WHERE published="1" '	;
	$db->setQuery( $query );
	$categories = $db->loadObjectList();
	
	$lists = array();
	$lists['calendar'] = $calendar;
	$lists['calendar_name'] = $calendar_name;
	$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', $row->published);
	
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
	
	
	$lists['monthly_list'] = JHTML::_('select.genericlist', $monthly_listOptions, 'monthly_list', 'class="inputbox"', 'value', 'text', $row->monthly_list);
	$lists['month_week'] = JHTML::_('select.genericlist', $month_weekOptions, 'month_week', 'class="inputbox"', 'value', 'text', $row->month_week);
	$lists['year_month'] = JHTML::_('select.genericlist', $year_monthOptions, 'year_month', 'class="inputbox"', 'value', 'text', $row->year_month);

	// display function 
	HTML_contact::editNote($lists, $row, $categories);
}

function removeNote()
{

  $mainframe = JFactory::getApplication();;
  // Initialize variables	
  $db =JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);

  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement
    $query = 'DELETE FROM #__spidercalendar_event'
    . ' WHERE id IN ( '. $cids .' )'
    ;
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
    }
  }

  // After all, redirect again to frontpage
  $mainframe->redirect( "index.php?option=com_spidercalendar&task=event&calendar=".JRequest::getVar('calendar'),'','message' );
}

function changeNote( $state=0 )
{
  $mainframe = JFactory::getApplication();;

  // Initialize variables
  $db 	=JFactory::getDBO();

  // define variable $cid from GET
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );	
  JArrayHelper::toInteger($cid);

  // Check there is/are item that will be changed. 
  //If not, show the error.
  if (count( $cid ) < 1) {
    $action = $state ? 'publish' : 'unpublish';
    JError::raiseError(500, JText::_( 'Select an item 
    to' .$action, true ) );
  }

  // Prepare sql statement, if cid more than one, 
  // it will be "cid1, cid2, cid3, ..."
  $cids = implode( ',', $cid );

  $query = 'UPDATE #__spidercalendar_event'
  . ' SET published = ' . (int) $state
  . ' WHERE id IN ( '. $cids .' )'
  ;
  // Execute query
  $db->setQuery( $query );
  if (!$db->query()) {
    JError::raiseError(500, $db->getErrorMsg() );
  }

  if (count( $cid ) == 1) {
    $row =JTable::getInstance('spidercalendar_event', 'Table');
    $row->checkin( intval( $cid[0] ) );
  }

  // After all, redirect to front page
  $mainframe->redirect( 'index.php?option=com_spidercalendar&task=event&calendar='.JRequest::getVar('calendar'),'','message' );
}



function cancelNote()
{
  $mainframe = JFactory::getApplication();;
  $mainframe->redirect( 'index.php?option=com_spidercalendar&task=event&calendar='.JRequest::getVar('calendar'),'','message' );

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// C A L E N D A R ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



function show_calendar(){
	$option	= JRequest::getVar('option'); 
	$mainframe = JFactory::getApplication();;
    $db =JFactory::getDBO();
	$filter_order= $mainframe-> getUserStateFromRequest( $option.'filter_order_calendar', 'filter_order_calendar','id','cmd' );
	//$filter_order='id';
	$filter_order_Dir= $mainframe-> getUserStateFromRequest( $option.'filter_order_Dir_calendar', 'filter_order_Dir_calendar','desc','word' );
	$search_calendar = $mainframe-> getUserStateFromRequest( $option.'search_calendar', 'search_calendar','','string' );
	$search_calendar = JString::strtolower( $search_calendar );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	$where = array();
	
	if ( $search_calendar ) {
		$where[] = 'title LIKE "%'.$db->escape($search_calendar).'%"';
	}	
	
	$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
	if ($filter_order == 'id'){
		$orderby 	= ' ORDER BY id '.$filter_order_Dir;
	} else {
		$orderby 	= ' ORDER BY '. 
         $filter_order .' '. $filter_order_Dir .', id';
	}	
	
	
	// get the total number of records
	$query = "SELECT COUNT(*) FROM #__spidercalendar_calendar". $where;

	$db->setQuery( $query );
	$total = $db->loadResult();
	jimport('joomla.html.pagination');
	$pageNav = new JPagination( $total, $limitstart, $limit );	
	
	$query = "SELECT * FROM #__spidercalendar_calendar". $where. $orderby;
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	
	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;
		
	// search_calendar filter	
        $lists['search_calendar']= $search_calendar;	
	
    // display function
	
	
	
	HTML_contact::show_calendar($rows, $pageNav, $lists);
}


function edit_calendar(){	
	$db		=JFactory::getDBO();
	$cid 	= JRequest::getVar('cid', array(0), '', 'array');
	JArrayHelper::toInteger($cid, array(0));

	$id 	= $cid[0];
	$row =JTable::getInstance('spidercalendar_calendar', 'Table');
	// load the row from the db table
	$row->load( $id);
	
	$lists = array();
	if($row->published=='')
	$row->published=1;
	$lists['published'] = JHTML::_('select.booleanlist', 'published' , 'class="inputbox"', $row->published);
	$lists['allow_publish'] = JHTML::_('select.booleanlist', 'allow_publish' , 'class="inputbox"', $row->allow_publish);
	$lists['get_email'] = JHTML::_('select.booleanlist', 'get_email' , 'class="inputbox"', $row->get_email);
		$lists['time_format'] = JHTML::_('select.booleanlist', 'time_format' , 'class="inputbox"', $row->time_format);

	$user =JFactory::getUser()->getAuthorisedGroups();
	
	$query = "SELECT * FROM #__usergroups";
	$db->setQuery( $query );
	$userGroups = $db->loadObjectList();
	
	//def_month Select List
  $def_month = array(''=>'Current',1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
  $def_monthOptions = array();
  foreach($def_month as $key=>$value) 
    	$def_monthOptions[] = JHTML::_('select.option',$key, $value);
 //end
	
	
	$lists['def_month'] = JHTML::_('select.genericlist', $def_monthOptions, 'def_month', 'class="inputbox"', 'value', 'text', $row->def_month);
	
	
	$lists['gid'] 	= JHTML::_('select.genericlist',   $userGroups, 'gid', 'class="inputbox"', 'id', 'title', $row->get('gid') );
	
	
	

	// display function 
	HTML_contact::edit_calendar($lists, $row, $themes,$userGroups);
}

function save_calendar(){
	$mainframe = JFactory::getApplication();
	$row =JTable::getInstance('spidercalendar_calendar', 'Table');
	

	if(!$row->bind(JRequest::get('post')))
	{
		JError::raiseError(500, $row->getError() );
	}
	$task		= JRequest::getCmd( 'task' );
	


	
	if(!$row->store()){
		JError::raiseError(500, $row->getError() );
	}
	
	switch ($task)
		{
			case 'apply_calendar' :
				$msg = JText::sprintf('Changes to calendar saved');
				$mainframe->redirect('index.php?option=com_spidercalendar&task=edit_calendar&cid[]='.$row->id, $msg,'message');
				break;

			case 'save_calendar' :
			
				$msg = JText::sprintf('calendar Saved');
				$mainframe->redirect('index.php?option=com_spidercalendar', $msg,'message');
				break;
			
		}
	
	
	
	$mainframe->redirect($link, $msg,'message');
}

function remove_calendar(){
  $mainframe = JFactory::getApplication();;
  // Initialize variables	
  $db =JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);
  
  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement

    $query = 'DELETE FROM #__spidercalendar_calendar'.' WHERE id IN ( '. $cids .' )';
    // Execute query
    
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
	  
    }
	
	
	
  }
  // After all, redirect again to frontpage
  $mainframe->redirect( "index.php?option=com_spidercalendar",'','message');
}




function change_calendar($state=0){
  $mainframe = JFactory::getApplication();;
  // Initialize variables
  $db 	=JFactory::getDBO();
  // define variable $cid from GET
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );	
  JArrayHelper::toInteger($cid);
  // Check there is/are item that will be changed. 
  //If not, show the error.
  if (count( $cid ) < 1) {
    $action = $state ? 'publish_calendar' : 'unpublish_calendar';
    JError::raiseError(500, JText::_( 'Select an item to' .$action, true ) );
  }
  // Prepare sql statement, if cid more than one, 
  // it will be "cid1, cid2, cid3, ..."
  $cids = implode( ',', $cid );
  $query = 'UPDATE #__spidercalendar_calendar' . ' SET published = '.(int) $state .' WHERE id IN ( '. $cids .' )'  ;
  // Execute query
  $db->setQuery( $query );
  if (!$db->query()) {
    JError::raiseError(500, $db->getErrorMsg() );
  }
  if (count( $cid ) == 1) {
    $row =JTable::getInstance('spidercalendar_calendar', 'Table');
    $row->checkin( intval( $cid[0] ) );
  }
  // After all, redirect to front page
  $mainframe->redirect( 'index.php?option=com_spidercalendar','','message' );
}

function cancel_calendar(){
  $mainframe = JFactory::getApplication();;
  $mainframe->redirect( 'index.php?option=com_spidercalendar&task=calendar','','message' );
}

//////////////////////////RECENT EVENTS ////////////////////////////////////////////////////////////////////////////////////
function module_event()
{
$option= JRequest::getVar( 'option');
	$mainframe = JFactory::getApplication();;
	
    $db =JFactory::getDBO();
  $cal=JRequest::getVar('cal',0);
	  $cal=JRequest::getVar('cal',0);

if($cal=='')
$cal=0;
	
	$filter_order= $mainframe->getUserStateFromRequest( $option.'filter_order_note','filter_order_note','id','cmd' );
	$filter_order_Dir= $mainframe->getUserStateFromRequest( $option.'filter_order_Dir_note',	'filter_order_Dir_note','','word' );
	$filter_state = $mainframe->getUserStateFromRequest( $option.'filter_state_note', 	'filter_state_note', '','word' );
	
	
	$search_note = $mainframe->getUserStateFromRequest( $option.'search_note','search_note','','string' );
	$search_note = JString::strtolower( $search_note );
	$limit= $mainframe-> getUserStateFromRequest('global.list.limit',  'limit', $mainframe->getCfg('list_limit'), 'int');
	$limitstart= $mainframe-> getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
	
	$lists = array();
	$where = array();
$lists['search_note']= $mainframe->getUserStateFromRequest( $option.'search_note','search_note','','string' );
$lists['search_note']= JString::strtolower($lists['search_note']);
$lists['startdate']= JRequest::getVar('startdate', "");
$lists['enddate']= JRequest::getVar('enddate', "");
if ( $lists['search_note'] ) {
		$where[] = ' se.title LIKE "%'.$db->escape($search_note).'%" AND se.calendar="'.$cal.'" AND se.published="1"';
	}
if($lists['search_note']){	
 if($lists['startdate']!='')
$where[] ="  `date`>='".$db->escape($lists['startdate'])."' ";
  if($lists['enddate']!='')
$where[] ="  `date`<='".$db->escape($lists['enddate'])."' ";
}
else
{
if($lists['startdate']!='')
$where[] ="  `event_day`>='".$db->escape($lists['startdate'])."' AND calendar='".$cal."' ";
  if($lists['enddate']!='')
$where[] ="  `event_day`<='".$db->escape($lists['enddate'])."' AND calendar='".$cal."'  ";

}
$filter='';
$filter=( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );
if(count( $where )==0)
{
$filter.=' WHERE calendar='.$cal.' AND published="1"';
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
	
	
	$query = "SELECT * FROM #__spidercalendar_event  ". $filter.$orderby;
	$db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
	$rows = $db->loadObjectList();
	if($db->getErrorNum()){
		echo $db->stderr();
		return false;
	}
	
	
	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;	

	// search filter	
        $lists['search_note']= $search_note;	
	
    // display function
	

	if($cal!=0)
	HTML_contact::module_event($rows, $pageNav, $lists);
else
echo '<div style="text-align:center;"><p style="font-size:25px">Please select calendar</p><div>';



}



?>