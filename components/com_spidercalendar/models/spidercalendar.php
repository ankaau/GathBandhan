<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/


Defined ('_JEXEC')  or  die();



jimport( 'joomla.application.component.model' );



class spidercalendarModelspidercalendar extends JModelLegacy



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

	function GetNextDate($beginDate,$repeat)
				{
				
				   //explode the date by "-" and storing to array
				   $date_parts1=explode("-", $beginDate);
				   
				   //gregoriantojd() Converts a Gregorian date to Julian Day Count
				   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
				  
				   return jdtogregorian($start_date+$repeat);
				   
				 
				}
				
	function daysDifference($beginDate,$endDate)
				{
				
				   //explode the date by "-" and storing to array
				   $date_parts1=explode("-", $beginDate);
				   $date_parts2=explode("-", $endDate);
				   
				   //gregoriantojd() Converts a Gregorian date to Julian Day Count
				   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
				   
				   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
				  
				   return $end_date-$start_date;
				   
				 
				}			



	function showcalendar()



	{
		$module = JRequest::getVar( "module_id");
		$date=JRequest::getVar( "date".$module."",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$calendar	=(int)JRequest::getVar('calendar_id',0);
		
$session =JFactory::getSession();

$date=JRequest::getVar( "date".$module."",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$year=substr( $date,0,4); 
		$month=substr( $date,5,2); 
	$date_format=$session->get( 'date_format'.$module);	
		
		$option=JRequest::getVar('option');



		$id=JRequest::getVar('id');

		$cat_ids = JRequest::getVar('cat_ids');

     //  var_dump($cat_ids);
		$db =JFactory::getDBO();

			$query="SELECT #__spidercalendar_event.* FROM #__spidercalendar_event WHERE #__spidercalendar_event.published=1 AND ((date<='".$db->escape($date)."') or ( date_end is Null and date='".$db->escape($date)."' )  )   " ;
		
		if($calendar!=0)
			{
				$query.=" AND calendar=$calendar ";
			}		

		$db->setQuery($query ); 



		$rows = $db->loadAssocList();

			
			$id_array=array();
		
		$s = count($rows);
		
		$array_days=array();
		$array_days1=array();
		$title=array();
		$title1=array();
		$eventIDs=array();
		///mec FOR
		
		for($i=1; $i<=$s; $i++)
		{			
		
			if($rows[$i-1]['repeat_method']!='no_repeat' and $rows[$i-1]['date_end']=='0000-00-00' )
			$d_end=((int)substr( $rows[$i-1]['date'],0,4)+40).substr( $rows[$i-1]['date'],4,6);
			else
			$d_end=$rows[$i-1]['date_end'];
			
			$date_month=(int)substr( $rows[$i-1]['date'],5,2);
			$date_end_month=(int)substr( $d_end,5,2);
			
			$date_day=(int)substr( $rows[$i-1]['date'],8,2);
			$date_end_day=(int)substr( $d_end,8,2);
			//echo $date_day;
			$date_year_month=(int)(substr( $rows[$i-1]['date'],0,4).substr( $rows[$i-1]['date'],5,2));
			$date_end_year_month=(int)(substr( $d_end,0,4).substr( $d_end,5,2));
			
			$year_month=(int)($year.$month);
			$repeat=$rows[$i-1]['repeat'];
			
			if ($repeat=="")
				$repeat=1;
				
			$start_date = $rows[$i-1]['date'];
			
		//echo $date_month.'<br>' ;
		
		//echo (int)$month.'<br>';
		
			$weekly=$rows[$i-1]['week'];			
			$weekly_array=explode(',',$weekly);
			 
			
			$date_days=array();
			$weekdays_start=array();
			$weekdays=array();
			
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////                NO Repeat                /////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			if($rows[$i-1]['repeat_method']=='no_repeat')
			{			
				$date_days[]=$date_day;
			}
			
			
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////               Repeat   Daily             /////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			if($rows[$i-1]['repeat_method']=='daily')
			{
				
				
				 
					$t = $this->daysDifference($rows[$i-1]['date'],$d_end);
					
					for($k=1;$k<=$t/$repeat;$k++){
				
						
					$next_date=$this->GetNextDate($start_date,$repeat);
					$next_date_array=explode('/',$next_date);
						
					
					
					if((int)$month==$date_month && (int)substr($date_year_month,0,4)==(int)$year)
						$date_days[0]=$date_day;
					
					
					
					
						if((int)$month==$next_date_array[0] && (int)$year==$next_date_array[2])
					
					$date_days[]=$next_date_array[1];
					$start_date = date("Y-m-d",mktime(0, 0, 0, $next_date_array[0], $next_date_array[1],$next_date_array[2]));
					
					
				
				}
			}
			
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////               Repeat   Weekly             ///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			
			
			
			if($rows[$i-1]['repeat_method']=='weekly')
			{
				
			
					
				
					
				for($j=0; $j<=6;$j++)
				{
					if( in_array(date("D", mktime(0, 0, 0, $date_month, $date_day+$j, substr($rows[$i-1]['date'],0,4))),$weekly_array))
					{	$weekdays_start[]=$date_day+$j;}
					
			
				}
				
				
		
				
				
				for($p=0;$p<count($weekly_array)-1;$p++)
				{
										
					$start_date = substr($rows[$i-1]['date'],0,8).$weekdays_start[$p];
					$t = $this->daysDifference($rows[$i-1]['date'],$d_end);
					$r=0;
					for($k=1;$k<$t/$repeat;$k++){
				

				
				$start_date_array[]=$start_date;
				
					$next_date=$this->GetNextDate($start_date,$repeat* 7);
					$next_date_array=explode('/',$next_date);
					
					
			
					
				if((int)$month==$date_month && (int)substr($date_year_month,0,4)==(int)$year)
						$date_days[0]=$weekdays_start[$p];
					
					
					
						if((int)$month==$next_date_array[0] && (int)$year==$next_date_array[2])
					if((int)$year>(int)substr($date_year_month,0,4)){
				
					$weekdays[]=$next_date_array[1];
					}
					else
					{
				
					$weekdays[]=$next_date_array[1];
					
					}
					
					$start_date = date("Y-m-d",mktime(0, 0, 0, $next_date_array[0], $next_date_array[1],$next_date_array[2]));
					
					
					if($next_date_array[2]>(int)substr($d_end,0,4))
					break;
					}
				
				$date_days=array_merge($weekdays,$date_days);
			
			
				}
				
			
				
				$repeat= $repeat * 7;
				
				
				
			}

		
		
		
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////               Repeat   Monthly            ///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
			
			if($rows[$i-1]['repeat_method']=='monthly')
			{
				
				$month_days = date('t',mktime(0, 0, 0, $month, $date_day, $year));
				if($date_month<(int)$month || (int)substr($date_year_month,0,4)<$year)
						$date_day=1;
				
				if($year>(int)substr($date_year_month,0,4))
				$date_year_month = $year.'00';
				
		
			
				$p=(int)substr($date_year_month,4,2);
				
				if((int)substr($date_year_month,0,4)!=(int)substr($date_end_year_month,0,4) )
					$end = (int)substr($date_end_year_month,4,2)+12;
				else
					$end = (int)substr($date_end_year_month,4,2);
				
					for($k=1; $k<=$end;$k++)
						{
																
						 if((int)$month==$p and $rows[$i-1]['month_type']==1)
									 {
								 $date_days[0]=$rows[$i-1]['month'];
								 	
								 
									 }
									 
									 
						if($p==(int)$month and $rows[$i-1]['month_type']==2)
									 {
								 if($rows[$i-1]['monthly_list']!='last'){
								 for($j=$rows[$i-1]['monthly_list']; $j<$rows[$i-1]['monthly_list']+7;$j++)
											{
												if(date("D", mktime(0, 0, 0, $month, $j, $year)) == $rows[$i-1]['month_week'])
													{	
													if($j>=$date_day)
													$date_days[0]=$j;
												
													
													}
											}
								 }
								 
								 else
								 {
								 for($j=1; $j<$month_days;$j++)
											{
												if(date("D", mktime(0, 0, 0, $month, $j, $year)) == $rows[$i-1]['month_week'])
													{	
													if($j>=$date_day)
													$date_days[0]=$j;
													
													}
												
											}
										
								 }
									 
									
							 
									 }		
						if($year>(int)substr($date_year_month,0,4))
						$p=1;
				
						$p=$p+$repeat;
						
					
						
							}
					$repeat=32;
					
					}
					
				
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////               Repeat   Yearly             ///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
					
			if($rows[$i-1]['repeat_method']=='yearly')
			{
				
			$month_days = date('t',mktime(0, 0, 0, $month, $date_day, $year));
				
				$end =	substr($date_end_year_month,0,4)-substr($date_year_month,0,4)+1;
							if(substr($date_year_month,0,4)<$year)
							$date_day=1;
				
							for($k=0; $k<=$end; $k+=$repeat)		{					
						 if((int)$month==$rows[$i-1]['year_month'] and $rows[$i-1]['month_type']==1 and $year==substr($date_year_month,0,4)+$k)
									 {
								 $date_days[0]=$rows[$i-1]['month'];
									 }
									 
								
							}
							for($k=0; $k<=$end; $k+=$repeat)		{		 
						if((int)$month==$rows[$i-1]['year_month'] and $rows[$i-1]['month_type']==2 and $year==substr($date_year_month,0,4)+$k)
									 {
								 if($rows[$i-1]['monthly_list']!='last'){
								 for($j=$rows[$i-1]['monthly_list']; $j<$rows[$i-1]['monthly_list']+7;$j++)
											{
												if(date("D", mktime(0, 0, 0, $month, $j, $year)) == $rows[$i-1]['month_week'])
													{	
													$date_days[0]=$j;
													
													}
											}
								 }
								 
								 else
								 {
								 for($j=1; $j<=$month_days;$j++)
											{
												if(date("D", mktime(0, 0, 0, $month, $j, $year)) == $rows[$i-1]['month_week'])
													{	
													$date_days[0]=$j;
													
													}
												
											}
								 }
									 
									 
									 }	
									 
							}
						
						$repeat=32;
					
			}
			
			$used=array();
			
			
			foreach($date_days as $date_day)
			{
				if($date_month==$month)
				{	
									
					if(in_array($date_day, $used))
						continue;
					else
						array_push($used, $date_day);

					if(in_array($date_day, $array_days))
					{
						$key = array_search($date_day, $array_days); 
						$title_num[$date_day]++;
						
						if($rows[$i-1]['text_for_date']!="")
							$array_days1[$key] = $date_day;
							

						
						
						
						$eventIDs[$date_day] = $eventIDs[$date_day].$rows[$i-1]['id'].'<br>';
						
							
					}
					else
					{
						$array_days[] = $date_day;
						$key = array_search($date_day, $array_days); 
						if($rows[$i-1]['text_for_date']!="")
							$array_days1[$key] = $date_day;
							
						$title_num[$date_day]=1;
						
					
						$eventIDs[$date_day] = $rows[$i-1]['id'].'<br>';
						
					}
					
					//$date_day=$date_day+$repeat;
				}
					
					if($date_end_month>0 and  $date_year_month==$date_end_year_month and $date_end_year_month==$year_month )
					for($j=$date_day;$j<=$date_end_day;$j=$j+$repeat)
					{	
					
					 	if(in_array($j, $used))
							continue;
						else
							array_push($used, $j);
					
						if(in_array($j, $array_days))
						{
							$key = array_search($j, $array_days); 
							$title_num[$j]++;
							
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
	
							
							
							$eventIDs[$j] = $eventIDs[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
					
						$eventIDs[$j] = $rows[$i-1]['id'].'<br>';
						}
					}
					
					
					
				
					if($date_end_month>0 and  $date_year_month<$date_end_year_month and $date_year_month==$year_month)
					
					for($j=$date_day;$j<=31;$j=$j+$repeat)
					{	
					
					 	if(in_array($j, $used))
							continue;
						else
							array_push($used, $j);
					
						if(in_array($j, $array_days))
						{
							$key = array_search($j, $array_days); 
							$title_num[$j]++;
							
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
	
							
							
							$eventIDs[$j] = $eventIDs[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
							
						
						$eventIDs[$j] = $rows[$i-1]['id'].'<br>';
						
						}
					}
					
					if($date_end_month>0 and  $date_year_month<$date_end_year_month and   $date_end_year_month==$year_month)
					
					for($j=$date_day;$j<=$date_end_day;$j=$j+$repeat)
					{	
					
					 	if(in_array($j, $used))
							continue;
						else
							array_push($used, $j);
					
						if(in_array($j, $array_days))
						{
							$key = array_search($j, $array_days); 
							$title_num[$j]++;
							
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
	
							
						
							$eventIDs[$j] = $eventIDs[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
							
						
						$eventIDs[$j] = $rows[$i-1]['id'].'<br>';
						}
					}
					
					
					if($date_end_month>0 and  $date_year_month<$date_end_year_month and   $date_end_year_month>$year_month and  $date_year_month<$year_month )
					
						for($j=$date_day;$j<=31;$j=$j+$repeat)
						{	
					
					 	if(in_array($j, $used))
							continue;
						else
							array_push($used, $j);
					
							if(in_array($j, $array_days))
							{
							$key = array_search($j, $array_days); 
							$title_num[$j]++;
							
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
	
							
							
							$eventIDs[$j] = $eventIDs[$j].$rows[$i-1]['id'].'<br>';
						}
							else
							{
								$array_days[] = $j;
								$key = array_search($j, $array_days); 
								if($rows[$i-1]['text_for_date']!="")
									$array_days1[$key] = $j;
									
								$title_num[$j]=1;
								
								
							
							$eventIDs[$j] = $rows[$i-1]['id'].'<br>';
							}
						}
				
				
				
			}
				
		}
		
	
		


		if ($db->getErrorNum())



		{



			echo $db->stderr();



			return false;



		}	


$meta_content="";
	
	foreach($rows as $row)
	$meta_content.=$row['title'].",";
		
	$document=JFactory::getDocument(); 
    $document->setMetadata("keywords", $meta_content.$document->getMetadata("keywords"));


		return array($rows,$option,$eventIDs);



	}



}

	?>