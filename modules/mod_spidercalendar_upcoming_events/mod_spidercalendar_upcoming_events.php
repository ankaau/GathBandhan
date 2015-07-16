<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
 $db = JFactory::getDBO();
JHTML::_('behavior.modal'); 
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_spidercalendar/tables');
$Itemid=JRequest::getVar( "Itemid","");
$session =JFactory::getSession();
$id= $module->id;

$show_numbering = $params->get('show_numbering');
$show_time = $params->get('show_time');
$show_repeat = $params->get('show_repeat');
$moduleclass_sfx = $params->get('moduleclass_sfx');
$date_format = 'd F Y';
$calendar_select = (int)$params->get('calendar_select');
$event_type = $params->get('event_type');
$event_from_current_day = $params->get('event_from_current_day');
$event_from_day_interval = $params->get('event_from_day_interval');
$since = $params->get('since');
$count = $params->get('count');
$ordering = $params->get('ordering');
$start_day_calendar = $params->get('start_day_calendar');
$since1 = $params->get('since1');
$count1 = $params->get('count1');
$ordering1 = $params->get('ordering1');
$event_select = $params->get('event_select');
$show_eventtext = $params->get('show_eventtext');
$hr = '#C2C2C2';

$bg_color = "#FFFFFF";
$text_color = "#000000";
$title_color = "#000000";
$date_color = "#000000";
$repeat_color = "#000000";
$title_size = '14';
$title_font ='Arial';
$popup_style = '13';

$lang = JFactory::getLanguage();
$extension = 'com_spidercalendar';
$base_dir = JPATH_SITE;
$language_tag = $lang->getTag();
$reload = true;
$lang->load($extension, $base_dir, $language_tag, $reload);


$query="SELECT popup_width,popup_height FROM #__spidercalendar_theme WHERE id=".$popup_style;
$db->setQuery($query);
$popup_w_h=$db->loadRow();

global $ar;
$ar=$ar+1;
$cal= strtotime($start_day_calendar);
if($ar==1)
{
?>

<script>
//////////////////////////////////POP-UP RESPONSIVE 
jQuery.fn.scr_center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, ((jQuery(window).height() - jQuery(this).outerHeight()) / 2) + 
                                                jQuery(window).scrollTop()) + "px");
    this.css("left", Math.max(0, ((jQuery(window).width() - jQuery(this).outerWidth()) / 2) + 
                                                jQuery(window).scrollLeft()) + "px");
    return this;
}

jQuery(window).resize(function(){
	jQuery('#sbox-window').scr_center();
	width_orig=<?php echo  $popup_w_h[0] ?>;
	height_orig=<?php echo  $popup_w_h[1] ?>;
		
		if(jQuery(window).width()<640)
			{
				width=jQuery(window).width()-100;
				height=jQuery(window).height()-100
				jQuery('.modal').attr('rel',"{handler: 'iframe', size: {x: "+width+", y: "+height+"}}")
				jQuery('#sbox-window').css('width',width+'px')
				jQuery('#sbox-window').css('height',height+'px')
				jQuery('#sbox-content iframe').css('width',width+'px');
				jQuery('#sbox-content iframe').css('height',height+'px');
			}
		else
			{
				jQuery('.modal').attr('rel',"{handler: 'iframe', size: {x: "+width_orig+", y: "+height_orig+"}}")
				jQuery('#sbox-window').css('width',width_orig+'px')
				jQuery('#sbox-window').css('height',height_orig+'px')
				jQuery('#sbox-content iframe').css('width',width_orig+'px');
				jQuery('#sbox-content iframe').css('height',height_orig+'px');			
			
			}



})

/////////////////////////////////////
</script>
<?php
function compare_str_to_array($string,$array,$end_date)
{

	foreach($array as $value)
	{
		if($string<=$value and $end_date>=$value)
		{
			return $value;
			break;
		}

	}


}


function compare_str_to_array1($string,$array)
{

	foreach($array as $value)
	{
		if($string<=$value)
		{
			return $value;
			break;
		}

	}


}



 function week_convert_recent($x)

 {
	if($x=='Mon')
	return JText::_('MONDAY'); 
	if($x=='Tue')
	return JText::_('TUESDAY'); 
	if($x=='Wed')
	return JText::_('WEDNESDAY'); 
	if($x=='Thu')
	return JText::_('THURSDAY'); 
	if($x=='Fri')
	return JText::_('FRIDAY');
	if($x=='Sat')
	return JText::_('SATURDAY');
	if($x=='Sun')
	return JText::_('SUNDAY');
 }

function sorrt($st_date,$array,$en_date)
{
$ids=array();
$dat=array();
$dats=array();
$ret_array=array();
foreach($array as $key=>$value)
{
$ids[]=$key;
$dat[]=compare_str_to_array($st_date,$value,$en_date);
$dats[]=$value;
}

asort($dat);

foreach($dat as $key=>$val)
{
$ret_array[$ids[$key]]=$dats[$key];
}

return $ret_array;

}



function sorrt1($st_date,$array)
{
$ids=array();
$dat=array();
$dats=array();
$ret_array=array();
foreach($array as $key=>$value)
{
$ids[]=$key;
$dat[]=compare_str_to_array1($st_date,$value);
$dats[]=$value;
}

asort($dat);

foreach($dat as $key=>$val)
{
$ret_array[$ids[$key]]=$dats[$key];
}

return $ret_array;

}


function add_00($str)
{

	if(strlen($str)==1)
		return '0'.$str;
	else
		return $str;


}

function num_to_str($x)
{
switch($x)
{
case '1':
return 'first';
break;

case '8':
return 'second';
break;

case '15':
return 'third';
break;

case '22':
return 'Fourth';
break;

case 'last':
return 'last';
break;
}
}

function upcoming_date($date_format,$date)
{
$date_formats=explode(' ',$date_format);

return JText::_(date($date_formats[0],strtotime($date))).' '.JText::_(date($date_formats[1],strtotime($date))).' '.JText::_(date($date_formats[2],strtotime($date)));


}

}
?>

<style type="text/css">

#event_repeat<?php echo $id?>{
color:<?php echo $repeat_color ?>;
padding-top:14px;
<?php if($show_eventtext==0){?>
padding-bottom:14px!important;

<?php }?>

}
#event_table<?php echo $id?>{

border:0px !important;
border-spacing:0px !important;
width:100%  !important;
border-collapse:collapse;

}

#event_text<?php echo $id?>{
padding:15px;
color:<?php echo $text_color?>;
padding-bottom:14px!important;
<?php if($show_repeat==0) {?>
padding-top:14px!important;
<?php } ?>
padding-left: 8px;
} 


#event_date<?php echo $id?>
{
color:<?php echo $date_color?>;
<?php if($show_eventtext==0){?>
padding-bottom:14px!important;
<?php } ?>
<?php if($show_repeat==0){?>
padding-bottom:14px!important;
<?php } ?>
}



#title<?php echo $id?>:link
{
font-size:<?php echo $title_size?>px;
font-family:<?php echo $title_font?>;
color:<?php echo $title_color?>!important;
text-decoration:none;

}

#title<?php echo $id?>:hover{
background:none ;
text-decoration:underline ;

}



 tr, td{
 border:0px;
 padding-left:7px;
 padding-right:12px;
padding-bottom:4px;
padding-top:2px;
 }
 #divider<?php echo $id?>
 {
 background-color:<?php echo $hr?>;
 border:none; 
 height:1px;
 margin: 0px;
 }

.pad
{
<?php if($show_time==0){?>
padding-bottom:14px;
<?php } ?>
}

.module<?php echo $id?>
{
background-color:<?php echo $bg_color?>;
border:1px ;
border-radius:8px;
-moz-border-radius: 8px;
 -webkit-border-radius: 8px;
padding:4px;
border:2px solid #6A6A6A;;

}
 
</style>



<?php

$query1= "SELECT * FROM #__spidercalendar_event WHERE published='1'";
$db->setQuery( $query1 );
$rows = $db -> loadObjectList();
 $daysarray = array();

 
foreach($rows as $row)
{
  if($row -> date_end!='0000-00-00')
  {
$Startdate = $row->date;
$Enddate = $row -> date_end ;
 
$ts1 = strtotime($Startdate);
$ts2 = strtotime($Enddate);

$seconds_diff = ($ts2 - $ts1);
$day_diff =  floor($seconds_diff/3600/24+1);




for($i=0; $i<$day_diff; $i+=$row->repeat)
{
$Nextdate = strtotime(date("Y-m-d", strtotime($row->date)) . " +".$i." day");

$Nextdate =  date("Y-m-d",$Nextdate);
 array_push($daysarray,$Nextdate);

}


$weekdays = explode(',',$row->week);
$weekdays= array_slice($weekdays,0,count($weekdays)-1);





}
 }//end main foreach
////////////////////////////////////////////////

 echo '<div class="module'.$id.'">';


if($event_type==0)
{



	$query=" SELECT * FROM   #__spidercalendar_event 
 WHERE calendar= ".$calendar_select."  AND published='1' ORDER BY RAND()";				

 $db->setQuery( $query );
$evs = $db->loadObjectList(); 
$st_date=date('Y-m-d');
	
//print_r($evs);
		
$dates=array();
foreach($evs as $ev)
{

$st=$ev->date;
if($ev->date_end!='0000-00-00')
$en=$ev->date_end;
else
$en=date('Y-m-d', strtotime('+24 year', strtotime($st)));

$date_st=explode('-',$st);

$date_end=explode('-',$en);

$st_d= mktime(0, 0, 0,  $date_st[1],  $date_st[2], $date_st[0]);
$en_d = mktime(0, 0, 0,  $date_end[1],  $date_end[2], $date_end[0]);

$weekly_array=explode(',',$ev->week);
		for($j=0; $j<=6;$j++)
					{
						if( in_array(date("D", mktime(0, 0, 0, $date_st[1], $date_st[2]+$j, $date_st[0])),$weekly_array))
						{	
						
						$weekdays_start[]=$date_st[2]+$j;}
							
					}


					
if($ev->repeat_method=="no_repeat")
{
$dates[$ev->id][0]=$ev->date;
}					

///////////////////get days for daily repeat 
if($ev->repeat_method=="daily")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat);
$dates[$ev->id][0]=$ev->date;
for($i=0;$i<$day_count;$i++)
	{
	if($ev->repeat_method=="daily")
	$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' day', strtotime($dates[$ev->id][$i])));
	}
}
///////////////////get days for weekly repeat 
if($ev->repeat_method=="weekly")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat*7);

$d=array();
$dat=array();
for($j=0;$j<count($weekdays_start);$j++)
	{

	unset($dat);
	$dat[0]=$date_st[0].'-'.$date_st[1].'-'.add_00($weekdays_start[$j]);

		
		for($i=0;$i<$day_count-1;$i++)
			{
				$dat[]=date('Y-m-d', strtotime('+'.($ev->repeat).' week', strtotime($dat[$i])));
			}
			
	
			$d=array_merge($d,$dat);
	}	

sort($d);
		$dates[$ev->id]=$d;
}	

///////////////////get days for monthly repeat 
if($ev->repeat_method=="monthly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$month_count = 0;

while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
    $month_count++;
}

$month_days = date('t',mktime(0, 0, 0, $date_st[1], 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	
			for($i=0;$i<$month_count;$i++)
			{
				$mon=date('F', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				
				date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));

				if($ev->month_type==1)
				$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				else
				{
				$mon=date('F', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));				
				$dates[$ev->id][]=date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));	
				}
			}
	
	

}


if($ev->repeat_method=="yearly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$year_count = 0;

while (($min_date = strtotime("+1 year", $min_date)) <= $max_date) {
    $year_count++;
}

$month_days = date('t',mktime(0, 0, 0, add_00($ev->year_month), 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	
			for($i=0;$i<$year_count;$i++)
			{


				if($ev->month_type==1)
				$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));
				else
				{
				$mon=date('F', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));				
				$dates[$ev->id][]=date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));	
				}
			}
	
	

}


sort($dates[$ev->id]);

}




foreach($dates as $ev_id=>$date )
{
//echo compare_str_to_array($st_date,$date,$en_date). ' ';

$gag[$ev_id]=compare_str_to_array1($st_date,$date);
}

$dates=sorrt1($st_date,$dates);

$i=1;	
$j=0;	

$p=0;
foreach($dates as $ev_id=>$date)
{



	$curr_event =JTable::getInstance('spidercalendar_event', 'Table');
	$curr_event->load($ev_id);

	if(compare_str_to_array1($st_date,$date)=='')
	continue;

$event_id = $curr_event->id;
$event_title = $curr_event->title;
$event_date = compare_str_to_array1($st_date,$date);
$event_end_date = $curr_event->date_end;
$event_text = $curr_event->text_for_date;
$calendar_id = $curr_event->calendar;
$repeat = $curr_event->repeat;

$year=substr($event_date,0,4);
$month=substr($event_date,5,-3);
$day=substr($event_date,8);
$week=explode(',',$curr_event->week);

/*$month_date_year = date("F j, Y",mktime(0,0,0,$month,$day,$year));
$jd=gregoriantojd($month,$day,$year);
$weekday = jddayofweek($jd,2);
$date = $weekday.' '.$month_date_year; 
echo $weekday;*/

echo '<div id="event_table'.$id.'" >';
if($show_numbering==1) 
{
echo '<div class="pad"><a id= "title'.$id.'"  class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&theme_id='.$popup_style.'&module_id='.$id.'&date='.$year.'-'.$month.'-'.$day).'&tmpl=component&Itemid='.$Itemid.'" ></br><b>'. $i++.'.'.$event_title.'</b></a></div>';
}
else
{
echo '<div class="pad"><a class="modal" id="title'.$id.'" rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}" 
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&theme_id='.$popup_style.'&module_id='.$id.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" ></br><b>'.$event_title.'</b></a></div>';

}	

?>

<style>
<?php if($event_text==''){?>
td #event_date<?php echo $id?> 
{
padding-bottom:14px;

} 
<?php }?>
</style>

<?php

if($show_time==1)
echo '<div id="event_date'.$id.'">'.upcoming_date($date_format,$event_date).'</div>';

if($show_repeat==1)
{
if($event_text=='')
{

if($curr_event->repeat_method=="no_repeat")
echo '<div id="event_repeat'.$id.'">'.JText::_('NO_REPEAT').'</div>';
else
{
echo '<div id="event_repeat'.$id.'" >';

	if($curr_event->repeat_method=='daily')
	echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';

		if($curr_event->repeat_method=='weekly')

		{

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';

		for ($g=0;$g<count($week);$g++) 

		{

			if($week[$g]!=''){

				if($g!=count($week)-2)

					echo week_convert_recent($week[$g]).',';

				else

					echo week_convert_recent($week[$g]);

			

			}

			

		}

		echo '</div>';

		}

		if($curr_event->repeat_method=='monthly' and $curr_event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$curr_event->month.'</div>';	



		if($curr_event->repeat_method=='monthly' and $curr_event->month_type==2)

		echo '<div>'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number_up($curr_event->monthly_list).' '.week_convert_recent($curr_event->month_week).'</div>';



		if($curr_event->repeat_method=='yearly' and $curr_event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$curr_event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$curr_event->month.'</div>';	



		if($curr_event->repeat_method=='yearly' and $curr_event->month_type==2)

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$curr_event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number_up($curr_event->monthly_list).' '.week_convert_recent($curr_event->month_week).'</div>';		






echo '</div>';	
}

}
else
{
if($curr_event->repeat_method=="no_repeat")
echo '<div id="event_repeat'.$id.'" >'.JText::_('NO_REPEAT').'</div>';
else
{
echo '<div id="event_repeat'.$id.'" >';

	if($curr_event->repeat_method=='daily')
	echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';

		if($curr_event->repeat_method=='weekly')

		{

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';

		for ($g=0;$g<count($week);$g++) 

		{

			if($week[$g]!=''){

				if($g!=count($week)-2)

					echo week_convert_recent($week[$g]).',';

				else

					echo week_convert_recent($week[$g]);

			

			}

			

		}

		echo '</div>';

		}

		if($curr_event->repeat_method=='monthly' and $curr_event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$curr_event->month.'</div>';	



		if($curr_event->repeat_method=='monthly' and $curr_event->month_type==2)

		echo '<div>'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number_up($curr_event->monthly_list).' '.week_convert_recent($curr_event->month_week).'</div>';



		if($curr_event->repeat_method=='yearly' and $curr_event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$curr_event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$curr_event->month.'</div>';	



		if($curr_event->repeat_method=='yearly' and $curr_event->month_type==2)

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$curr_event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number_up($curr_event->monthly_list).' '.week_convert_recent($curr_event->month_week).'</div>';		






echo '</div>';	
}
}
}

if($show_eventtext==1)
{
if($event_text)
{
//var_dump($event_text);
//$length = strlen($event_text);
$text = mb_substr(html_entity_decode(strip_tags($event_text)), 0, 50);

echo '<div id="event_text'.$id.'" >'.$text;

echo '<a class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  style="text-decoration:none;"
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" >'.JText::_("...see more").'</a></div>';


}
}


echo '<div style="padding-top:6px"><hr id="divider'.$id.'"/></div>';
$j++;
echo'</div>';


$p++;
if($p==$event_from_current_day)
break;
}



}


if($event_type==1)
{
if($event_from_day_interval==0)		
{			
$st_date=date('Y-m-d');
$en_date=date('Y-m-d', strtotime('+'.$since.' day', strtotime($st_date)));
if($ordering==0)
$order="ORDER BY  time DESC";
else
$order="ORDER BY RAND()";

$limit=$count;
}
else
{
$st_date=$start_day_calendar;
$en_date=date('Y-m-d', strtotime('+'.$since1.' day', strtotime($st_date)));
if($ordering1==0)
$order="ORDER BY  time DESC";
else
$order="ORDER BY RAND()";
$limit=$count1;
}

//$count=(((strtotime($en_date)-strtotime($st_date))/3600)/24);



	$query=" SELECT * FROM   #__spidercalendar_event 
 WHERE calendar= ".$calendar_select."  AND  published='1' ".$order ;	
$db->setQuery( $query );
$evs= $db->loadObjectList(); 



$dates=array();
foreach($evs as $ev)
{

$st=$ev->date;
if($ev->date_end!='0000-00-00')
$en=$ev->date_end;
else
$en=date('Y-m-d', strtotime('+24 year', strtotime($st)));

$date_st=explode('-',$st);

$date_end=explode('-',$en);

$st_d= mktime(0, 0, 0,  $date_st[1],  $date_st[2], $date_st[0]);
$en_d = mktime(0, 0, 0,  $date_end[1],  $date_end[2], $date_end[0]);

$weekly_array=explode(',',$ev->week);
		for($j=0; $j<=6;$j++)
					{
						if( in_array(date("D", mktime(0, 0, 0, $date_st[1], $date_st[2]+$j, $date_st[0])),$weekly_array))
						{	
						
						$weekdays_start[]=$date_st[2]+$j;}
							
					}


					
if($ev->repeat_method=="no_repeat")
{
$dates[$ev->id][0]=$ev->date;
}					

///////////////////get days for daily repeat 
if($ev->repeat_method=="daily")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat);
$dates[$ev->id][0]=$ev->date;
for($i=0;$i<$day_count;$i++)
	{
	if($ev->repeat_method=="daily")
	$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' day', strtotime($dates[$ev->id][$i])));
	}
}
///////////////////get days for weekly repeat 
if($ev->repeat_method=="weekly")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat*7);

$d=array();
$dat=array();
for($j=0;$j<count($weekdays_start);$j++)
	{

	unset($dat);
	$dat[0]=$date_st[0].'-'.$date_st[1].'-'.add_00($weekdays_start[$j]);

		
		for($i=0;$i<$day_count-1;$i++)
			{
				$dat[]=date('Y-m-d', strtotime('+'.($ev->repeat).' week', strtotime($dat[$i])));
			}
			
	
			$d=array_merge($d,$dat);
	}	

sort($d);
		$dates[$ev->id]=$d;
}	

///////////////////get days for monthly repeat 
if($ev->repeat_method=="monthly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$month_count = 0;

while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
    $month_count++;
}

$month_days = date('t',mktime(0, 0, 0, $date_st[1], 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	
			for($i=0;$i<$month_count;$i++)
			{
				$mon=date('F', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				
				date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));

				if($ev->month_type==1)
				$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				else
				{
				$mon=date('F', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' month', strtotime($dates[$ev->id][$i])));				
				$dates[$ev->id][]=date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));	
				}
			}
	
	

}


if($ev->repeat_method=="yearly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$year_count = 0;

while (($min_date = strtotime("+1 year", $min_date)) <= $max_date) {
    $year_count++;
}

$month_days = date('t',mktime(0, 0, 0, add_00($ev->year_month), 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	
			for($i=0;$i<$year_count;$i++)
			{


				if($ev->month_type==1)
				$dates[$ev->id][]=date('Y-m-d', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));
				else
				{
				$mon=date('F', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));
				$year=date('Y', strtotime('+'.($ev->repeat).' year', strtotime($dates[$ev->id][$i])));				
				$dates[$ev->id][]=date('Y-m-d', strtotime(''.num_to_str($ev->monthly_list).' '.$ev->month_week.' of '.$mon.' '.$year.''));	
				}
			}
	
	

}


sort($dates[$ev->id]);

}




foreach($dates as $ev_id=>$date )
{
//echo compare_str_to_array($st_date,$date,$en_date). ' ';

$gag[$ev_id]=compare_str_to_array($st_date,$date,$en_date);
}

if($ordering==0 or $ordering1==0)
$dates=sorrt($st_date,$dates,$en_date);



$i=1;	
$j=0;	

$p=0;
foreach($dates as $ev_id=>$date)
{


if(compare_str_to_array($st_date,$date,$en_date)=='')
	continue;
	
if(compare_str_to_array($st_date,$date,$en_date)!='')
{

	$order_event_current_day =JTable::getInstance('spidercalendar_event', 'Table');
	$order_event_current_day->load($ev_id);

		
				

   $event_id = $order_event_current_day->id; 
   $event_title = $order_event_current_day->title;
   $event_date =compare_str_to_array($st_date,$date,$en_date);
   $event_end_date = $order_event_current_day->date_end;
   $event_text = $order_event_current_day->text_for_date;
   $calendar_id = $order_event_current_day->calendar;
   $repeat = $order_event_current_day->repeat;
   
   $year=substr($event_date,0,4);
   $month=substr($event_date,5,-3);
   $day=substr($event_date,8);
   
   
   
		$week=explode(',',$order_event_current_day->week);

   
   
   echo '<div id="event_table'.$id.'" >';
 if($show_numbering==1) 
{
echo '<div style="padding-top:14px;" class="pad"><a id= "title'.$id.'"  class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day).'&tmpl=component&Itemid='.$Itemid.'" ><b>'. $i++.'.'.$event_title.'</b></a></div>';
}
else
{
echo '<div style="padding-top:14px;" class="pad" ><a class="modal" id="title'.$id.'"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}" 
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" ><b>'.$event_title.'</b></a></div>';
}
	
?>

<style>
<?php if($event_text==''){?>
td #event_date<?php echo $id?> 
{
padding-bottom:14px;

} 
<?php }?>
</style>

<?php
if($show_time==1)
echo '<div id="event_date'.$id.'">'.upcoming_date($date_format,$event_date).'</div>';

if($show_repeat==1)
{

if($order_event_current_day->repeat_method=="no_repeat")
echo '<div id="event_repeat'.$id.'" >'.JText::_('NO_REPEAT').'</div>';
else
{
echo '<div id="event_repeat'.$id.'" >';

	if($order_event_current_day->repeat_method=='daily')
	echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';

		if($order_event_current_day->repeat_method=='weekly')

		{

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';

		for ($g=0;$g<count($week);$g++) 

		{

			if($week[$g]!=''){

				if($g!=count($week)-2)

					echo week_convert_recent($week[$g]).',';

				else

					echo week_convert_recent($week[$g]);

			

			}

			

		}

		echo '</div>';

		}

		if($order_event_current_day->repeat_method=='monthly' and $order_event_current_day->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$order_event_current_day->month.'</div>';	



		if($order_event_current_day->repeat_method=='monthly' and $order_event_current_day->month_type==2)

		echo '<div>'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number_up($order_event_current_day->monthly_list).' '.week_convert_recent($order_event_current_day->month_week).'</div>';



		if($order_event_current_day->repeat_method=='yearly' and $order_event_current_day->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$order_event_current_day->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$order_event_current_day->month.'</div>';	



		if($order_event_current_day->repeat_method=='yearly' and $order_event_current_day->month_type==2)

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$order_event_current_day->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number_up($order_event_current_day->monthly_list).' '.week_convert_recent($order_event_current_day->month_week).'</div>';		






echo '</div>';	
}


}
if($show_eventtext==1)
{

if($event_text)
{
$text = mb_substr(html_entity_decode(strip_tags($event_text)), 0, 50);

echo '<div id="event_text'.$id.'" >'.$text;
echo '<a class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  style="text-decoration:none;"
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" >'.JText::_("...see more").'</a></div>';

}
}


echo '<div style="padding-top:6px"><hr id="divider'.$id.'"/></div>';
$j++;
echo'</div>';
}

$p++;
if($p==$limit)
break;
}
 
}

if($event_type==2)
{
$events=array();
$events_id=explode(',',$event_select);
	
				$events_id= array_slice($events_id,1, count($events_id)-2);
				
		foreach($events_id as $event_id)
				{
				
					$query ="SELECT * FROM #__spidercalendar_event WHERE id=".$db->escape($event_id);
						
					$db->setQuery($query); 
					$events[] = $db->loadObject();

					if ($db->getErrorNum())
					{
						echo $db->stderr();
						return false;
					}	
				}
				

$dates=array();
foreach($events as $ev)
{

$st=$ev->date;
if($ev->date_end!='0000-00-00')
$en=$ev->date_end;
else
$en=date('Y-m-d', strtotime('+24 year', strtotime($st)));

$date_st=explode('-',$st);

$date_end=explode('-',$en);

$st_d= mktime(0, 0, 0,  $date_st[1],  $date_st[2], $date_st[0]);
$en_d = mktime(0, 0, 0,  $date_end[1],  $date_end[2], $date_end[0]);

$weekly_array=explode(',',$ev->week);
		for($j=0; $j<=6;$j++)
					{
						if( in_array(date("D", mktime(0, 0, 0, $date_st[1], $date_st[2]+$j, $date_st[0])),$weekly_array))
						{	
						
						$weekdays_start[]=$date_st[2]+$j;}
							
					}
					
if($ev->repeat_method=="no_repeat")
{
$dates[$ev->id][0]=$ev->date;
}		


///////////////////get days for daily repeat 
if($ev->repeat_method=="daily")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat);
$dates[$ev->id][0]=$ev->date;

}
///////////////////get days for weekly repeat 
if($ev->repeat_method=="weekly")
{
$day_count=((($en_d-$st_d)/3600)/24)/($ev->repeat*7);

$d=array();
$dat=array();
for($j=0;$j<count($weekdays_start);$j++)
	{

	unset($dat);
	$dat[]=$date_st[0].'-'.$date_st[1].'-'.add_00($weekdays_start[$j]);


			
	
			$d=array_merge($d,$dat);
	}	

sort($d);
		$dates[$ev->id]=$d;

}	
///////////////////get days for monthly repeat 
if($ev->repeat_method=="monthly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$month_count = 0;

while (($min_date = strtotime("+1 MONTH", $min_date)) <= $max_date) {
    $month_count++;
}

$month_days = date('t',mktime(0, 0, 0, $date_st[1], 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, $date_st[1], $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.$date_st[1].'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	

	
	

}


if($ev->repeat_method=="yearly")
{

$start_date = strtotime($ev->date);
$end_date = strtotime($en);
$min_date = min($start_date, $end_date);
$max_date = max($start_date, $end_date);
$year_count = 0;

while (($min_date = strtotime("+1 year", $min_date)) <= $max_date) {
    $year_count++;
}

$month_days = date('t',mktime(0, 0, 0, add_00($ev->year_month), 1, $date_st[0]));

	if($ev->month_type==1)
	{
	$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($ev->month);

	}
	else
	{
		if($ev->monthly_list!='last'){
			for($j=$ev->monthly_list; $j<$ev->monthly_list+7;$j++)
				{
				if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	


						$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
							
													
					}
				}
			}
		else
			{
			 for($j=1; $j<=$month_days;$j++)
				{
					if(date("D", mktime(0, 0, 0, add_00($ev->year_month), $j, $date_st[0])) == $ev->month_week)
					{	
					$dates[$ev->id][0]=$date_st[0].'-'.add_00($ev->year_month).'-'.add_00($j);
				
					}
												
				}
			}
			
			
	}
	

	
	

}


sort($dates[$ev->id]);

}





	
	$i=1;
	$j=0;
	$ev=0;
	
  foreach ($dates as $ev_id=>$date)
	 {

	 
	 	$event =JTable::getInstance('spidercalendar_event', 'Table');
	$event->load($ev_id);

	 
	 
	  $event_id = $event->id; 
      $event_title = $event->title;
   $event_date =$date[0];
	  $event_end_date = $event->date_end;
      $event_text = $event->text_for_date;
      $calendar_id = $event->calendar;
      $repeat = $event->repeat;
	  		$week=explode(',',$event->week);

  $published = $event->published;
   $year=substr($event_date,0,4);
   $month=substr($event_date,5,-3);
   $day=substr($event_date,8);
   
   if( $published == 1){
   echo '<div id="event_table'.$id.'" >';
 if($show_numbering==1) 
{
echo '<div style="padding-top:0px;" class="pad"><a id= "title'.$id.'"  class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day).'&tmpl=component&Itemid='.$Itemid.'" ></br><b>'. $i++.'.'.$event_title.'</b></a></div>';
}
else
{
echo '<div style="padding-top:0px;" class="pad"><a class="modal" id="title'.$id.'"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" ></br><b>'.$event_title.'</b></a></div>';
}
	
?>

<style>
<?php if($event_text==''){?>
td #event_date<?php echo $id?> 
{
padding-bottom:14px;

} 
<?php }?>
</style>

<?php
if($show_time==1)
echo '<div id="event_date'.$id.'">'.upcoming_date($date_format,$event_date).'</div>';

if($show_repeat==1)
{

if($event->repeat_method=="no_repeat")
echo '<div id="event_repeat'.$id.'" >'.JText::_('NO_REPEAT').'</div>';
else
{
echo '<div id="event_repeat'.$id.'" >';

	if($event->repeat_method=='daily')
	echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';

		if($event->repeat_method=='weekly')

		{

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';

		for ($g=0;$g<count($week);$g++) 

		{

			if($week[$g]!=''){

				if($g!=count($week)-2)

					echo week_convert_recent($week[$g]).',';

				else

					echo week_convert_recent($week[$g]);

			

			}

			

		}

		echo '</div>';

		}

		if($event->repeat_method=='monthly' and $event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$event->month.'</div>';	



		if($event->repeat_method=='monthly' and $event->month_type==2)

		echo '<div>'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number_up($event->monthly_list).' '.week_convert_recent($event->month_week).'</div>';



		if($event->repeat_method=='yearly' and $event->month_type==1)

		echo '<div>'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$event->month.'</div>';	



		if($event->repeat_method=='yearly' and $event->month_type==2)

		echo '<div >'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$event->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number_up($event->monthly_list).' '.week_convert_recent($event->month_week).'</div>';		






echo '</div>';		
}


}
if($show_eventtext==1)
{

if($event_text)
{

//$length = strlen($event_text);
$text = mb_substr(html_entity_decode(strip_tags($event_text)), 0, 50);

echo '<div id="event_text'.$id.'"><span>'.$text.'</span>';
echo '<a class="modal"  rel="{handler: \'iframe\',size: {x: '.$popup_w_h[0].', y: '.$popup_w_h[1].'}}"  style="text-decoration:none;"
href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&calendar_id='.$calendar_id.'&eventID='.$event_id.'&module_id='.$id.'&theme_id='.$popup_style.'&date='.$year.'-'.$month.'-'.$day ).'&tmpl=component&Itemid='.$Itemid.'" >'.JText::_("...see more").'</a></div>';

}
}

if($j<count($events)-1)
echo '<div style="padding-top:6px"><hr id="divider'.$id.'"/></div>';
	$j++;
echo'</div>';
}

$ev++;
}
}

echo '</div>';

?>