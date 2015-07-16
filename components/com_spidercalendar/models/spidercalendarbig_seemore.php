<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/


Defined ('_JEXEC')  or  die();



jimport( 'joomla.application.component.model' );



class spidercalendarModelspidercalendarbig_seemore extends JModelLegacy
{




	function Month_num($month_name)

		

	{

 
		for( $month_num=1; $month_num<=12; $month_num++ )

		

		{  

		    if (date( "F", mktime(0, 0, 0, $month_num, 1, 0 ) ) == $month_name)

			    

		    {  

			return $month_num;  

				

		    } 

			     

		}

			

	}

	
	function showevent()



	{
		
		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$calendar	=(int)JRequest::getVar('calendar_id',0);
		
		$session =JFactory::getSession();

		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$year=substr( $date,0,4); 
		$month=substr( $date,5,2); 
		$date_format=$session->get( 'date_format');	
		
		$option=JRequest::getVar('option');
		$db =JFactory::getDBO();
		

		//$query = "SELECT * FROM #__spidercalendar_event where calendar=".$db->escape($calendar)." ";
		$query = "SELECT #__spidercalendar_event_category.color AS color, #__spidercalendar_event_category.published as cat_published, #__spidercalendar_event.* FROM #__spidercalendar_event LEFT JOIN  #__spidercalendar_event_category  ON #__spidercalendar_event_category.id=
		#__spidercalendar_event.category where   calendar=".$db->escape($calendar)." 
		AND #__spidercalendar_event.published=1";
		$db->setQuery( $query );
			
	$db->setQuery( $query );
  
  $rows = $db->loadObjectList();
			
			
		return array($rows,$option);



	}

  function showcategoryevent()
  {
		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$cat_id=JRequest::getVar( "cat_id"); 
		$cat_ids=JRequest::getVar( "cat_ids"); 
	    $ev_ids =JRequest::getVar('ev_ids');
	    $ev_ids_array = explode(',',$ev_ids);
		$calendar	=(int)JRequest::getVar('calendar_id',0);
		
		$session =JFactory::getSession();

		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$year=substr( $date,0,4); 
		$month=substr( $date,5,2); 
		$date_format=$session->get( 'date_format');	
		
		$option=JRequest::getVar('option');
		$db =JFactory::getDBO();
		

		$query = "SELECT * FROM #__spidercalendar_event where calendar=".$db->escape($calendar)." AND id IN 
		(".$ev_ids.")";
			
	$db->setQuery( $query );
  
  $rows1 = $db->loadObjectList();
		
		$query = "SELECT #__spidercalendar_event_category.color AS color, #__spidercalendar_event.* FROM #__spidercalendar_event JOIN  #__spidercalendar_event_category  ON #__spidercalendar_event_category.id=
		#__spidercalendar_event.category where  calendar=".$db->escape($calendar)." AND #__spidercalendar_event_category.published=1
		AND #__spidercalendar_event.published=1 AND #__spidercalendar_event.id IN (".$ev_ids.")";
		$db->setQuery( $query );
  
         $catcolors = $db->loadObjectList();
		 
			
		return array($rows1,$option, $catcolors);
  }

}

	?>