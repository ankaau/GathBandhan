<?php

 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
Defined ('_JEXEC')  or  die();

jimport( 'joomla.application.component.model' );

class spidercalendarModelbigcalendarlist extends JModelLegacy

{

function getcategories()
{
$db =JFactory::getDBO();
	$query = "SELECT * FROM #__spidercalendar_event_category WHERE published='1'";

	$db->setQuery( $query );
	$categories = $db->loadObjectList();

	
	return array ($categories);

}

function getelementcountinarray($array , $element)
{
  $t=0; 

  for($i=0; $i<count($array); $i++)
  {
    if($element==$array[$i])
	$t++;
  
  }
  
  
  return $t; 

}

function getelementindexinarray($array , $element)
{
 
		$t='';
		
	for($i=0; $i<count($array); $i++)
		{
			if($element==$array[$i])
			$t.=$i.',';
	
	    }
	
	return $t;


}



	function Month_num($month_name)

	{
		for( $month_num=1; $month_num<=12; $month_num++ )
		
		{  
		    if (date( "F", mktime(0, 0, 0, $month_num, 1, 0 ) ) == $month_name and ($month_num<10) )
		    
		    {
			return '0'.$month_num;  
			
		    }
		    
		    else
		    
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
				
	
	
	
	
	
	
	
	
	function getdays()

	{
		$db =JFactory::getDBO();
		$option=JRequest::getVar('option');
		$cat_id = JRequest::getVar('cat_id'); 
		$cat_ids = JRequest::getVar('cat_ids'); 
		if($cat_ids =='' and $cat_id!='')				
		   $cat_ids = $cat_id;
		 
		else
		if($cat_ids!='' and $cat_id!='')
			$cat_ids .=','.$cat_id; 
		
		$cat_ids_array = explode(',',$cat_ids);
		
		if($this->getelementcountinarray($cat_ids_array,$cat_id )%2==0)
		{
			$index_in_line = $this->getelementindexinarray($cat_ids_array, $cat_id);
			$index_array = explode(',' , $index_in_line);
			array_pop ($index_array);
			for($j=0; $j<count($index_array); $j++)
				unset($cat_ids_array[$index_array[$j]]);
			$cat_ids = implode(',',$cat_ids_array);
		}

		$id=JRequest::getVar('id');
		
		$calendar	=JRequest::getVar('calendar');
		$module 	= JRequest::getVar( "module_id");
		
		$date=JRequest::getVar( "date",date("Y").'-'.$this->Month_num(date("F")).'-'.date("d")); 
		$year=substr( $date,0,4); 
		$month=substr( $date,5,2); 
		
		
			$theme_id =JRequest::getVar('theme_id');
		$theme 	=JTable::getInstance('spidercalendar_theme', 'Table');
			// load the row from the db table
		$theme->load( $theme_id);
			
		$show_time=$theme->show_time;
			
		
		
		
		$session =JFactory::getSession();
		$date_format=$session->get( 'date_format'.$module);
		
		
		if(!JRequest::getVar('calendar'))
			$calendar=0;
		if($cat_ids!='')
			$db->setQuery( "SELECT #__spidercalendar_event.*,#__spidercalendar_event_category.color  from #__spidercalendar_event JOIN #__spidercalendar_event_category ON #__spidercalendar_event.category = #__spidercalendar_event_category.id where #__spidercalendar_event_category.published=1 and #__spidercalendar_event.category IN (".$cat_ids.") and #__spidercalendar_event.published=1 and ( ( (date<='".$db->escape(substr( $date,0,7))."-01' or date like '".$db->escape(substr( $date,0,7))."%') and  (date_end>='".$db->escape(substr( $date,0,7))."-01' ) or date_end='0000-00-00'  ) or ( date_end is Null and date like '".$db->escape(substr( $date,0,7))."%' ) ) and calendar=$calendar ORDER BY #__spidercalendar_event.time ASC ");
		else
			$db->setQuery( "SELECT *  from #__spidercalendar_event  where  published=1 and ( ( (date<='".$db->escape(substr( $date,0,7))."-01' or date like '".$db->escape(substr( $date,0,7))."%') and  (date_end>='".$db->escape(substr( $date,0,7))."-01' ) or date_end='0000-00-00'  ) or ( date_end is Null and date like '".$db->escape(substr( $date,0,7))."%' ) ) and calendar=$calendar ORDER BY #__spidercalendar_event.time ASC ");

//echo	"SELECT date,date_end,text_for_date from #__spidercalendar where published=1 and ( ( (date<='".substr( $date,0,7)."-01' or date like '".substr( $date,0,7)."%') and  date_end>='".substr( $date,0,7)."-01' ) or ( date_end is Null and date like '".substr( $date,0,7)."%' ) )  ";
		
		

		$rows = $db->loadAssocList();

			
			$id_array=array();
		
		$s = count($rows);
		
		$array_days=array();
		$array_days1=array();
		$title=array();
		$ev_ids=array();
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
					$q = $this->daysDifference($rows[$i-1]['date'],$start_date);
					$r=0;
		
			
			if(($t/($repeat*7)-1)>1)
			{
					for($k=1;$k<=floor($t/($repeat*7));$k++){
	
				$start_date_array[]=$start_date;
				
					$next_date=$this->GetNextDate($start_date,$repeat* 7);
					$next_date_array=explode('/',$next_date);
				
			
			
				if((int)$month==$date_month && (int)substr($date_year_month,0,4)==(int)$year)
						$date_days[0]=$weekdays_start[$p];
					
					
					
						if((int)$month==$next_date_array[0] && (int)$year==$next_date_array[2])
						{
						
					if((int)$year>(int)substr($date_year_month,0,4)){
				
					$weekdays[]=$next_date_array[1];
					}
					else
					{
			
					$weekdays[]=$next_date_array[1];
					
					}
					}
					
					
					$start_date = date("Y-m-d",mktime(0, 0, 0, $next_date_array[0], $next_date_array[1],$next_date_array[2]));
					
					
					if($next_date_array[2]>(int)substr($d_end,0,4))
					break;
					}
					$date_days=array_merge($weekdays,$date_days);
				}
				else
				{
		
			if($t>=$q)
				$date_days[]=$weekdays_start[$p];
		
				}
				
				

				}
				

			
				
				$repeat= $repeat * 7;
				
				
				
			}
		
		
		
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////               Repeat   Monthly            ///////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					
			
			if($rows[$i-1]['repeat_method']=='monthly')
			{
				
				$month_days = date('t',mktime(0, 0, 0, $month, $date_day, $year));
				if($date_month<(int)$month or (int)substr($date_year_month,0,4)<$year )
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
							

						$c=$title_num[$date_day];
							  
							$list='<p>&nbsp; ';
									   
							
							if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
							
							
							$title[$date_day]=$title[$date_day].$list;
							
						
						
						$ev_ids[$date_day]=$ev_ids[$date_day].$rows[$i-1]['id'].'<br>';
						
						
							
					}
					else
					{
						$array_days[] = $date_day;
						$key = array_search($date_day, $array_days); 
						if($rows[$i-1]['text_for_date']!="")
							$array_days1[$key] = $date_day;
							
						$title_num[$date_day]=1;
						
						$c=1;
								  
						$list='<p>&nbsp; ';
								   							
						if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
						
						$title[$date_day]=$list;
						$ev_ids[$date_day]=$rows[$i-1]['id'].'<br>';
						
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
								
	
							$c=$title_num[$j];
								  
								$list='<p>&nbsp; ';
										   
								if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
								
								$title[$j]=$title[$j].$list;
							
							$ev_ids[$j]=$ev_ids[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
							$c=1;
									  
							$list='<p>&nbsp; ';
									   									
							if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
							
							$title[$j]=$list;
						$ev_ids[$j] = $rows[$i-1]['id'].'<br>';
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
								
	
							$c=$title_num[$j];
								  
								$list='<p>&nbsp; ';
										   
										
								if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
								
								$title[$j]=$title[$j].$list;
							
							$ev_ids[$j]=$ev_ids[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
							$c=1;
									  
							$list='<p>&nbsp; ';
									   
									
							if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
							
							$title[$j]=$list;
						$ev_ids[$j] = $rows[$i-1]['id'].'<br>';
						
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
								
	
							$c=$title_num[$j];
							  
								$list='<p>&nbsp; ';
										   										
								if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
								
								$title[$j]=$title[$j].$list;
							
							$ev_ids[$j]=$ev_ids[$j].$rows[$i-1]['id'].'<br>';
						}
						else
						{
							$array_days[] = $j;
							$key = array_search($j, $array_days); 
							if($rows[$i-1]['text_for_date']!="")
								$array_days1[$key] = $j;
								
							$title_num[$j]=1;
							
							$c=1;
									  
							$list='<p>&nbsp; ';
									   									
							if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
							
							$title[$j]=$list;
						$ev_ids[$j] = $rows[$i-1]['id'].'<br>';
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
								
	
							$c=$title_num[$j];
								  
								$list='<p>&nbsp; ';
										   									
								if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
								
								$title[$j]=$title[$j].$list;
							
							$ev_ids[$j]=$ev_ids[$j].$rows[$i-1]['id'].'<br>';
						}
							else
							{
								$array_days[] = $j;
								$key = array_search($j, $array_days); 
								if($rows[$i-1]['text_for_date']!="")
									$array_days1[$key] = $j;
									
								$title_num[$j]=1;
								
								$c=1;
										  
								$list='<p>&nbsp; ';
										   										
								if($rows[$i-1]['time'] and $show_time!=0)		
							$list.=$rows[$i-1]['title'].'<br>('.$rows[$i-1]['time'].')</p>';
							else
							$list.=$rows[$i-1]['title'].'</p>';
								
								$title[$j]=$list;
							$ev_ids[$j] = $rows[$i-1]['id'].'<br>';
							}
						}
				
					
				
			}
				
		}
	
		
		
	
		
		if ($db->getErrorNum())

		{

			echo $db->stderr();

			return false;

		}	

		for($i=1; $i<=count($array_days)-1; $i++)
		   if(isset($array_days[$i]))
			if($array_days[$i]>'00' && $array_days[$i]<'09' and substr( $array_days[$i],0,1)=='0')
				
				$array_days[$i] = substr( $array_days[$i],1,1);
			

		return array($array_days,$title, $option,$array_days1,$calendar,$ev_ids);


	}
	

}