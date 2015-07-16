<?php  

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
 defined('_JEXEC') or die('Restricted access'); 
 
 $theme_id =13;
$theme 	=JTable::getInstance('spidercalendar_theme', 'Table');
			// load the row from the db table
$theme->load( $theme_id);
 $cat_id=JRequest::getVar( "cat_id");$cat_ids=JRequest::getVar( "cat_ids");


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
		 
		 
		 $date_format='';
		 
		

		$like_button=$session->get( 'like_button'.$id);
 	
		$rows=$this->rows;		$rows1=$this->rows1;		$catcolors=$this->catcolors;
        $option=$this->option;
		$activedate=explode('-',JRequest::getVar( "date",date("Y-m-d")));				
		$activedatetimestamp = mktime(0, 0, 0, $activedate[1], $activedate[2], $activedate[0]);				
		$activedatestr=JText::_(date("l",$activedatetimestamp)).', '.JText::_(date("d",$activedatetimestamp)).' '.JText::_(date("F",$activedatetimestamp)).', '.JText::_(date("Y",$activedatetimestamp));		
		$date =  JRequest::getVar( "date",date("Y-m-d"));
		$day = substr($date,8);
		$calendar_id	=JRequest::getVar('calendar_id',0);
		

		
		 $ev_ids =JRequest::getVar('ev_ids');
	
    $ev_id = explode(',',$ev_ids);
	

 $document = JFactory::getDocument();	
 $cmpnt_js_path = JURI::root(true).'/administrator/components/com_spidercalendar/elements';
/*if (!JFactory::getApplication()->get('jquery')) {
JFactory::getApplication()->set('jquery', true);
$document->addScript($cmpnt_js_path.'/jquery.js');
}*/
 $document->addScript($cmpnt_js_path.'/jquery.js');
$eventID=JRequest::getVar('eventID');
 $cat_id = JRequest::getVar('cat_id'); $cat_ids = JRequest::getVar('cat_ids'); 
 
 
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
 
 
 
 
 
 ?><style>body{margin: 0px !important;}		#sbox-window{padding:0px !important;
}		#main{ padding: 0px !important;}	

</style>
  <script>
  
  
  function next(day_events,ev_id,theme_id,calendar_id,date,day,cat_id,cat_ids)
  {

  
	
	  var p=0;
	   for (var key in day_events)
	   {	  p=p+1;
		   if(day_events[key]==ev_id && day_events[parseInt(key) +1])
		   {
				   
					
				   window.location='index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='+theme_id+'&calendar_id='+calendar_id+'&eventID='+day_events[parseInt(key) +1]+'&date='+date+'&day='+day+'&cat_id='+cat_id+'cat_ids='+cat_ids+'&tmpl=component'
		  
		   }
		 	   		   
	  }

	  
  }
  
  
  function change()
  {
  
  $('#dayevent').ready(function() {
  $('#dayevent').animate({
      
    opacity: 1,
	
    marginLeft: "0in",
	
   
  }, 1000, function() {
  

    
  });
});
  
  }

  window.onload=change();
  
  function prev(array1,ev_id,theme_id,calendar_id,date,day)
  {
   var day_events = array1;
 
	   for (var key in day_events)
	   {	  
		   if(day_events[key]==ev_id && day_events[parseInt(key) -1] )
		   {
				   
					
				   window.location='index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='+theme_id+'&calendar_id='+calendar_id+'&eventID='+day_events[parseInt(key) -1]+'&date='+date+'&day='+day+'&cat_id='+cat_id+'cat_ids='+cat_ids+'&tmpl=component'
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
  
<div style="background-color:<?php echo $show_event_bgcolor ?>; height:<?php echo $popup_height-30 ?>px;padding-left: 2px;text-align:left;">
 <?php 

echo '<div style="font-weight:bold;text-align:center;border-bottom:1px solid #F3F3F3;color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; ">'.$activedatestr.'</div>';
$ik=1;
 if($cat_ids=='') {

 foreach($rows as $row)
 {
 
 
		if($row->repeat=='1')

		$repeat='';

		else

		$repeat=$row->repeat;
 
 		$weekdays=explode(',',$row->week);
		if($row->date_end!='0000-00-00')
	    $d_end=' - '.$row->date_end;
		else
		$d_end='';
		
		
		  
	for($i=0;$i<count($ev_id);$i++)	{
		$color=$row->color;
			
		if($row->cat_published==0 and $row->color!='')			
			$color='';		if($row->id==$ev_id[$i])
		{			echo '<div ><div style="display: table-cell;background-color: #E8E8E8;border-right:1px solid #'.$color.';width: 30px;text-align:center;border-top:1px solid white;font-size: 34px;font-weight: bold;color: white;">'.($i+1).'</div> <div  style="display: table-cell;vertical-align: middle;padding-left:10px;width: 96%;"><a  style="font-size:'.$title_size.'px;color:'.$title_color.'; line-height:30px;text-decoration:none" href="index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='.$theme_id.'&calendar_id='.$calendar_id.'&ev_ids='.$ev_ids.'&eventID='.$row->id.'&date='.$date.'&day='.$day.'&tmpl=component"><p style="font-size: 20px;"> '.$row->title .'</p></a>';
				

		if($row->repeat_method=='daily')

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;    "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div><div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').')</div></div>';

		if($row->repeat_method=='weekly')

		{

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;     "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('WEEK').' : ';

		for ($i=0;$i<count($weekdays);$i++) 

		{

			if($weekdays[$i]!=''){

				if($i!=count($weekdays)-2)

					echo week_convert($weekdays[$i]).',';

				else

					echo week_convert($weekdays[$i]);

			

			}

			

		}

		echo ')</div>';

		}

		if($row->repeat_method=='monthly' and $row->month_type==1)

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;    "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$row->month.')</div></div>';	



		if($row->repeat_method=='monthly' and $row->month_type==2)

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;  "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).')</div></div>';



		if($row->repeat_method=='yearly' and $row->month_type==1)

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%; padding: 3px; background-color: #F8F8F8; "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.$row->month.')</div></div>';	



		if($row->repeat_method=='yearly' and $row->month_type==2)

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;   "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.$d_end.' ('.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row->year_month + 1,0,0)).' '.JText::_('ON_THE').' '.week_number($row->monthly_list).' '.week_convert($row->month_week).')</div></div>';		



		if($row->repeat_method=='no_repeat')

		echo '<div style="color:#6B696A;font-size:'.$date_size.'px; font-family:'.$date_font.';width: 100%;padding: 3px;background-color: #F8F8F8;   "><div style="display:table-cell"><img src="administrator/components/com_spidercalendar/elements/calendar.png" /></div> <div style="display:table-cell;vertical-align:middle;padding-left:5px">Date: '.$row->date.' ('.JText::_('NO_REPEAT').')</div></div>';		

			
	echo '</div></div>';
			
			
			}	}
  } }   else  {    $ev_ids= '';    foreach($catcolors as $row)	  $ev_ids.=$row->id.',';	      $ev_ids = substr($ev_ids, 0,-1);	    foreach($catcolors as $row)    {		echo '<div ><div  style="display: table-cell;background-color: #E8E8E8;border-right:1px solid #'.$row->color.';width: 30px;text-align:center;border-right:1px solid white;font-size: 34px;font-weight: bold;color: white;">'.$ik.'</div> <div  style="display: table-cell;vertical-align: middle;"><a  style="font-size:'.$title_size.'px;color:'.$title_color.'; line-height:30px;text-decoration:none" href="index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='.$theme_id.'&calendar_id='.$calendar_id.'&ev_ids='.$ev_ids.'&eventID='.$row->id.'&date='.$date.'&day='.$day.'&cat_id='.$cat_id.'&cat_ids='.$cat_ids.'&tmpl=component"><p style="font-size: 20px;"> '.$row->title .'</p></a></div></div>';         $ik++;  }
 }
 
 
 
 
 ?>
 </div>
 
