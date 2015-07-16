<?php  
 /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/
 
 defined('_JEXEC') or die('Restricted access'); 
 
 $theme_id =13;
$theme 	=JTable::getInstance('spidercalendar_theme', 'Table');
			// load the row from the db table
$theme->load( $theme_id);
$cat_id= JRequest::getVar('cat_id');$cat_ids =JRequest::getVar('cat_ids');
 function week_number($x)
 {
	
	if($x==1)
	return JText::_('FIRST'); 
	
	if($x==8)
	return JText::_('SECOND');
	
	if($x==15)
	return JText::_('THIRD');
	
	if($x==22)
	return JText::_('FOURTH');
	
	if($x=='last')
	return JText::_('LAST'); 
 }
 
 
 
 function week_convert($x)
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
 
 
function do_nothing()
{
return false;

}

		$id=JRequest::getVar( "module_id");
		$session =JFactory::getSession();
 

 	 	 $title_color='#'.$theme->title_color;
		 
		 $title_size=$theme->title_font_size;
		 
		 $title_font=$theme->title_font;
		 
		 $title_style=$theme->title_style;
		 
		 $date_color='#'.$theme->date_color;
		 
		 $date_size=$theme->date_size;
		 
		 $date_font=$theme->date_font;
		 
		 $date_style=$theme->date_style;
		 
		 $next_prev_event_bgcolor='#'.$theme->next_prev_event_bgcolor;
		 $next_prev_event_arrowcolor='#'.$theme->next_prev_event_arrowcolor;
		 $show_event_bgcolor='#'.$theme->show_event_bgcolor;
		 
		 $popup_width = $theme->popup_width;
		 $popup_height = $theme->popup_height;
		 $show_repeat=$theme->show_repeat;
		 
		 $date_format=$theme->date_format;
		
		$date_format_array=explode('/',$date_format);
		
		for($i=0;$i<count($date_format_array);$i++)
		{
		if($date_format_array[$i]=='w')
			$date_format_array[$i]='l';
		
		if($date_format_array[$i]=='m')
			$date_format_array[$i]='F';	
		
		if($date_format_array[$i]=='y')
			$date_format_array[$i]='Y';		
		
		}
		

		$like_button=$session->get( 'like_button'.$id);
 	
		$row=$this->row;	
        $option=$this->option;
		$activedate=explode('-',JRequest::getVar( "date",date("Y-m-d")));				
		$activedatetimestamp = mktime(0, 0, 0, $activedate[1], $activedate[2], $activedate[0]);				
		$activedatestr='';
		for($i=0;$i<count($date_format_array);$i++)
		{
		$activedatestr.=JText::_(date("".$date_format_array[$i]."",$activedatetimestamp)).' ';
		}
		
		//$activedatestr=JText::_(date("".$date_format_array[0]."",$activedatetimestamp)).' '.JText::_(date("".$date_format_array[1]."",$activedatetimestamp)).' '.JText::_(date("".$date_format_array[2]."",$activedatetimestamp)).' '.JText::_(date("".$date_format_array[3]."",$activedatetimestamp));		
	
		$date =  JRequest::getVar( "date",date("Y-m-d"));
		$day = substr($date,8);
		$calendar_id	=JRequest::getVar('calendar_id',0);
		
		 //$ev_ids =$session->get('ev_ids');
	$ev_ids_inline=JRequest::getVar('ev_ids');
    $ev_id = explode(',',$ev_ids_inline);
	
   
 $document = JFactory::getDocument();	
 $cmpnt_js_path = 'administrator/components/com_spidercalendar/elements';
 /*if (!JFactory::getApplication()->get('jquery')) {
JFactory::getApplication()->set('jquery', true);
$document->addScript($cmpnt_js_path.'/jquery.js');
}
 */
 $document->addScript($cmpnt_js_path.'/jquery.js');   

 
$eventID=JRequest::getVar('eventID');

 ?>
 <script src="administrator/components/com_spidercalendar/elements/jquery.js" ></script>
  <script>
 /* x=window.parent.document.getElementsByTagName('iframe').length;
 for(i=1;i<x;i++)
  {
  if(window.parent.document.getElementsByTagName('iframe')[i])
window.parent.document.getElementsByTagName('iframe')[i].parentNode.removeChild(window.parent.document.getElementsByTagName('iframe')[i])
}*/


  function next(day_events,ev_id,theme_id,calendar_id,date,day,ev_ids,cat_id,cat_ids)
  {

  
	
	  var p=0;
	   for (var key in day_events)
	   {	  p=p+1;
		   if(day_events[key]==ev_id && day_events[parseInt(key) +1])
		   {
				   
					
				   window.location='index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='+theme_id+'&calendar_id='+calendar_id+'&eventID='+day_events[parseInt(key) +1]+'&date='+date+'&day='+day+'&ev_ids='+ev_ids+'&cat_id='+cat_id+'&cat_ids='+cat_ids+'&tmpl=component'
		  
		   }
		 	   		   
	  }

	  
  }
  
  
  function change()
  {
 
  jQuery('#dayevent').ready(function() {
  jQuery('#dayevent').animate({
      
    opacity: 1,
	
    marginLeft: "0in",
	
   
  }, 1000, function() {
  

    
  });
});
  
  }

  window.onload=change();
  
  function prev(array1,ev_id,theme_id,calendar_id,date,day,ev_ids,cat_id,cat_ids)
  {
   var day_events = array1;
 
	   for (var key in day_events)
	   {	  
		   if(day_events[key]==ev_id && day_events[parseInt(key) -1] )
		   {
				   
					
				   window.location='index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='+theme_id+'&calendar_id='+calendar_id+'&eventID='+day_events[parseInt(key) -1]+'&date='+date+'&ev_ids='+ev_ids+'&day='+day+'&cat_id='+cat_id+'&cat_ids='+cat_ids+'&tmpl=component'
		   }
	  }
	  
  
  
  
  }
  
  
 document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {

		window.parent.SqueezeBox.close();
    }
}; 


  
  </script>

 <?php
 

		?>
		
		<style>
		#sbox-window
		{
		padding:0px !important;
		}
		
		#dayevent
		{
		 opacity:0;
		
		}
		#previous  , #next
		{
		width:5%;
		height:<?php echo $popup_height - 6 ?>px;
		cursor:pointer;
		border:0px solid;
		vertical-align: middle;
		}
		
		.arrow
		{
		font-size:50px;
		color:<?php echo $next_prev_event_arrowcolor ?>;
		text-decoration:none;
		
		}
		tr, td 
			{
			border: solid 0px #DDD;
			}

			
table
{
border-collapse: initial;
border:0px;
}
table tr:hover td
{
background: none;

}
table td
 {
 padding: 0px;
vertical-align: none;
border-top:none;
line-height: none;
text-align: none;
 vertical-align:none;
 }
 
 table th, table td
 {
 vertical-align:none;
 }
 
p, ol, ul, dl, address
{
margin-bottom:0;

}body{margin: 0px !important;}
		#sbox-window{padding:0px !important;}
		#main{ padding: 0px !important;}		
		
		
		</style>
		
		
		<table id="show_event" style="height:<?php echo $popup_height ?>px;width:100%;background-color:<?php echo $show_event_bgcolor ?>; border-spacing:0"  align="center">
		<tr>
		
		<td  id="previous" onclick="prev([<?php echo $ev_ids_inline ?>],<?php echo $eventID ?>,<?php echo $theme_id ?>,<?php echo $calendar_id ?>,'<?php echo $date; ?>',<?php echo $day ?>,'<?php echo $ev_ids_inline ?>','<?php echo $cat_id?>','<?php echo $cat_ids?>')"  style="<?php if(count($ev_id)==1 or $eventID==$ev_id[0] ) echo 'display:none' ?>;text-align:center;border:0;" onmouseover="document.getElementById('previous').style.backgroundColor='<?php echo $next_prev_event_bgcolor ?>'" onmouseout="document.getElementById('previous').style.backgroundColor=''" >
		
		<span class="arrow"  >&lt;</span>
		
		</td>
		
		<td style="vertical-align:top; width:90%;text-align:left;">
		<?php
		
		echo '<div id="dayevent" style="padding:0px 0px 0px 7px ;line-height:30px; padding-top:0px;">';		
				
		
		if($date_style=="bold" or $date_style=="bold/italic" )
		$date_font_weight="font-weight:bold";
		else
		$date_font_weight="font-weight:normal";
		if($date_style=="italic" or $date_style=="bold/italic" )
		$date_font_style="font-style:italic";
		else
		$date_font_style="";
		

		echo '<div style="font-weight:bold;text-align:center;border-bottom:1px solid #F3F3F3;color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.$activedatestr.'</div>';
		if($title_style=="bold" or $title_style=="bold/italic" )
		$font_weight="font-weight:bold";
		else
		$font_weight="font-weight:normal";
		if($title_style=="italic" or $title_style=="bold/italic" )
		$font_style="font-style:italic";
		else
		$font_style="";
		
		$weekdays=explode(',',$row->week);
	
		$date_format1='d/m/y';
		
		if($row->repeat=='1')
		$repeat='';
		else
		$repeat=$row->repeat;								$cat=$row->category;		if($row->category=='')$cat=0;
						$db		=JFactory::getDBO();					$query = "SELECT color FROM #__spidercalendar_event_category WHERE id=".$cat;					$db->setQuery( $query );						$cat_color = $db->loadResult();										
		if($row->text_for_date!='')
		{		
		if($row->date_end and $row->date_end!='0000-00-00' and $row->date_end!=$row->date )					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('DATE').':'.str_replace("d",substr($row->date,8,2),str_replace("m",substr($row->date,5,2),str_replace("y",substr($row->date,0,4),$date_format1))).'&nbsp;-&nbsp;'.str_replace("d",substr($row->date_end,8,2),str_replace("m",substr($row->date_end,5,2),str_replace("y",substr($row->date_end,0,4),$date_format1))).'&nbsp;'.$row->time.'</div>';					else										if($row->date_end=='0000-00-00' AND $row->repeat_method!="no_repeat")					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('DATE').':'.str_replace("d",substr($row->date,8,2),str_replace("m",substr($row->date,5,2),str_replace("y",substr($row->date,0,4),$date_format1))).'&nbsp;'.$row->time.'</div>';					else					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$font_weight.'; '.$font_style.'  ">'.$row->time.'</div>';		


if($show_repeat==1)						
	{		
		if($row->repeat_method=='daily')
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';
		if($row->repeat_method=='weekly')
		{
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';
		for ($i=0;$i<count($weekdays);$i++) 
		{
			if($weekdays[$i]!=''){
				if($i!=count($weekdays)-2)
					echo week_convert($weekdays[$i]).',';
				else
					echo week_convert($weekdays[$i]);
			
			}
			
		}
		echo '</div>';
		}
		if($row->repeat_method=='monthly' and $row->month_type==1)
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$row->month.'</div>';	

		if($row->repeat_method=='monthly' and $row->month_type==2)
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).'</div>';

		if($row->repeat_method=='yearly' and $row->month_type==1)
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$row->month.'</div>';	

		if($row->repeat_method=='yearly' and $row->month_type==2)
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).'</div>';		

		if($row->repeat_method=='no_repeat')
		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('NO_REPEAT').'</div>';		
			
		}			

					echo '<div style="color:'.$title_color.';font-size:'.$title_size.'px; font-family:'.$title_font.'; '.$font_weight.'; '.$font_style.'  "><span style = "background-color:#'.$cat_color.';" >&nbsp;</span>&nbsp;'.$row->title.'</div>';

					echo '<div style="line-height:20px">'.$row->text_for_date.'</div>';
					
					$session->set('daytitle',$row->title);
					
					
					if($session->get('daytitle')!=$row->title)
					continue;
		}
		else
		{
		if($row->date_end and $row->date_end!='0000-00-00' and $row->date_end!=$row->date )					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('DATE').':'.str_replace("d",substr($row->date,8,2),str_replace("m",substr($row->date,5,2),str_replace("y",substr($row->date,0,4),$date_format1))).'&nbsp;-&nbsp;'.str_replace("d",substr($row->date_end,8,2),str_replace("m",substr($row->date_end,5,2),str_replace("y",substr($row->date_end,0,4),$date_format1))).'&nbsp;'.$row->time.'</div>';					else					if($row->date_end=='0000-00-00' AND $row->repeat_method!="no_repeat")					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('DATE').':'.str_replace("d",substr($row->date,8,2),str_replace("m",substr($row->date,5,2),str_replace("y",substr($row->date,0,4),$date_format1))).'&nbsp;'.$row->time.'</div>';					else					echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$font_weight.'; '.$font_style.'  ">'.$row->time.'</div>';																		if($show_repeat==1)							{				if($row->repeat_method=='daily')		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div>';		if($row->repeat_method=='weekly')		{		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';		for ($i=0;$i<count($weekdays);$i++) 		{			if($weekdays[$i]!=''){				if($i!=count($weekdays)-2)					echo week_convert($weekdays[$i]).',';				else					echo week_convert($weekdays[$i]);						}					}		echo '</div>';		}		if($row->repeat_method=='monthly' and $row->month_type==1)		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$row->month.'</div>';			if($row->repeat_method=='monthly' and $row->month_type==2)		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).'</div>';		if($row->repeat_method=='yearly' and $row->month_type==1)		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$row->month.'</div>';			if($row->repeat_method=='yearly' and $row->month_type==2)		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).'</div>';				if($row->repeat_method=='no_repeat')		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('NO_REPEAT').'</div>';							}				echo '<div style="color:'.$title_color.';font-size:'.$title_size.'px; font-family:'.$title_font.'; '.$font_weight.'; '.$font_style.'  "><span style = "background-color:#'.$cat_color.';" >&nbsp;</span> '.$row->title.'</div>';		
		echo '<div style="text-align:center;font-size: 18px;padding: 0;margin: 0;">There Is No Text For This Event</div>';
		}
		echo '</div>';	
		
	?>
	<div style="width:98%;text-align:right; display:<?php if(count($ev_id)==1) echo 'none'; ?>"><a style="color:<?php echo $title_color?>;font-size:15px; font-family:<?php echo $title_font?>; <?php echo $font_weight?>; <?php echo $font_style?>" href="<?php echo JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig_seemore&theme_id='.$theme_id.'&ev_ids='.$ev_ids_inline.'&calendar_id='.$calendar_id.'&date='.$date.'&cat_id='.$cat_id.'&cat_ids='.$cat_ids ).'&tmpl=component' ?>"><?php echo JText::_("BACK_TO_EVENT_LIST");  ?></a></div>
	</td>
	
	<td id="next"  onclick="next([<?php echo $ev_ids_inline ?>],<?php echo $eventID ?>,<?php echo $theme_id ?>,<?php echo $calendar_id ?>,'<?php echo $date ?>',<?php echo $day ?>,'<?php echo $ev_ids_inline ?>','<?php echo $cat_id?>','<?php echo $cat_ids ?>')"   style="<?php if(count($ev_id)==1 or $eventID==end($ev_id)) echo 'display:none' ?>;text-align:center;" onmouseover="document.getElementById('next').style.backgroundColor='<?php echo $next_prev_event_bgcolor ?>'" onmouseout="document.getElementById('next').style.backgroundColor=''" >
	
		<span class="arrow"  >&gt;</span>
		
		</td>
	
	</tr>
	
	</table>

