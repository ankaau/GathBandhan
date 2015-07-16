<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

Defined ('_JEXEC')  or  die();



jimport( 'joomla.application.component.model' );



class spidercalendarModelspidercalendarbig extends JModelLegacy
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
		$calendar	=JRequest::getVar('calendar_id',0);
		
		$session =JFactory::getSession();

		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$year=substr( $date,0,4); 
		$month=substr( $date,5,2); 
		$date_format=$session->get( 'date_format');	
		
		$option=JRequest::getVar('option');



		$eventID=JRequest::getVar('eventID');
		$row =JTable::getInstance('spidercalendar_event', 'Table');
			// load the row from the db table
		$row->load($eventID);
			

		return array($row,$option);



	}



}

	?>