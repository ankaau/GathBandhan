<?php  

 /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/
 
 defined('_JEXEC') or die('Restricted access'); 
  	$db =JFactory::getDBO();
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
 
 $id=JRequest::getVar( "module_id");
 $session =JFactory::getSession();
 
 	 	 $title_color=$session->get( 'title_color'.$id);
		 
		 $title_size=$session->get( 'title_size'.$id);
		 
		 $title_font=$session->get( 'title_font'.$id);
		 
		 $title_style=$session->get('title_style'.$id);
		 
		 $date_color=$session->get( 'date_color'.$id);
		 
		 $date_size=$session->get( 'date_size'.$id);
		 
		 $date_font=$session->get( 'date_font'.$id);
		 
		 $date_style=$session->get('date_style'.$id);
		 
		 $date_format=$session->get( 'date_format'.$id);
		 
		 $like_button=$session->get( 'like_button'.$id);
		 
		 $show_repeat=$session->get( 'show_repeat'.$id);
 	
		$rows=$this->rows;
        $option=$this->option;
		$activedate=explode('-',JRequest::getVar( "date".$id."",date("Y-m-d")));			$activedatetimestamp = mktime(0, 0, 0, $activedate[1], $activedate[2], $activedate[0]);			$activedatestr=JText::_(date("l",$activedatetimestamp)).', '.JText::_(date("d",$activedatetimestamp)).' '.JText::_(date("F",$activedatetimestamp)).', '.JText::_(date("Y",$activedatetimestamp));		
		$date =  JRequest::getVar( "date".$id,date("Y-m-d"));
		$day = substr($date,8);
		
		
		$eventIDs = $this->eventIDs;
		//print_r($_SESSION['titles'.$id]);
		
	
		
		
		@$eventID=explode('<br>',$eventIDs[$day]);
	
		
		if($date_style=="bold" or $date_style=="bold/italic" )
		$date_font_weight="font-weight:bold";
		else
		$date_font_weight="font-weight:normal";
		if($date_style=="italic" or $date_style=="bold/italic" )
		$date_font_style="font-style:italic";
		else
		$date_font_style="";
		

		echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.$activedatestr.'</div><br/>';

		foreach($rows as $row)

		{			$cat=$row['category'];		if($row['category']=='')$cat=0;							$query="SELECT color FROM #__spidercalendar_event_category WHERE id=".$cat;		$db->setQuery($query);		$row['color']=$db->loadResult();		
		if($row['repeat']=='1')
		$repeat='';
		else
		$repeat=$row['repeat'];
			
			if (in_array($row['id'],$eventID) && $row['text_for_date']!='')
			
			{
		if($title_style=="bold" or $title_style=="bold/italic" )
		$font_weight="font-weight:bold";
		else
		$font_weight="font-weight:normal";
		if($title_style=="italic" or $title_style=="bold/italic" )
		$font_style="font-style:italic";
		else
		$font_style="";
		
		$weekdays=explode(',',$row['week']);
		if($date_format=="")
		$date_format='d/m/y';
		
		
if($row['date_end'] and $row['date_end']!=$row['date'])
			echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('DATE').':'.str_replace("d",substr($row['date'],8,2),str_replace("m",substr($row['date'],5,2),str_replace("y",substr($row['date'],0,4),$date_format))).'&nbsp;-&nbsp;'.str_replace("d",substr($row['date_end'],8,2),str_replace("m",substr($row['date_end'],5,2),str_replace("y",substr($row['date_end'],0,4),$date_format))).'&nbsp;'.$row['time'].'</div>';
			else
						echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$font_weight.'; '.$font_style.'  ">'.$row['time'].'</div>';


if($show_repeat==1)						
	{							
if($row['repeat_method']=='daily')
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('DAY').'</div><br />';
if($row['repeat_method']=='weekly')
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
echo '</div><br />';
}
if($row['repeat_method']=='monthly' and $row['month_type']==1)
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('MONTH').' '.$row['month'].'</div><br />';	

if($row['repeat_method']=='monthly' and $row['month_type']==2)
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' '.$repeat.' '.JText::_('MONTH').' '.week_number($row['monthly_list']).' '.week_convert($row['month_week']).'</div><br />';

if($row['repeat_method']=='yearly' and $row['month_type']==1)
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row['year_month'] + 1,0,0)).' '.JText::_('ON_THE').' '.$row['month'].'</div><br />';	

if($row['repeat_method']=='yearly' and $row['month_type']==2)
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('REPEAT_EVERY').' ' .$repeat.' '.JText::_('YEAR').' '.date('F',mktime(0,0,0,$row['year_month'] + 1,0,0)).' '.JText::_('ON_THE').' '.week_number($row['monthly_list']).' '.week_convert($row['month_week']).'</div><br />';		

if($row['repeat_method']=='no_repeat')
echo '<div style="color:'.$date_color.';font-size:'.$date_size.'px; font-family:'.$date_font.'; '.$date_font_weight.'; '.$date_font_style.'  ">'.JText::_('NO_REPEAT').'</div><br />';		
	
			}
			
			echo '<div style="color:'.$title_color.';font-size:'.$title_size.'px; font-family:'.$title_font.'; '.$font_weight.'; '.$font_style.'  "><span style="border-left:5px solid #'.$row['color'].';">'.$row['title'].'</span></div><br />';

			echo $row['text_for_date'].'<br /><br />';
			
			$session->set('daytitle',$row['title']);
			
			
			if($session->get('daytitle')!=$row['title'])
			continue;
			}
		}
$str = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];


if($like_button==1)
	echo '<iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2F'.urlencode($str).'"
			scrolling="no" frameborder="0"
			style="border:none; width:450px; height:80px"></iframe>';
else

echo '';



	?>

