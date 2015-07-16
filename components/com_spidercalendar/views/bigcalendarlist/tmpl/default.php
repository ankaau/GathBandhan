<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$Itemid=JRequest::getVar( "Itemid","");

		 
$id=JRequest::getVar( "rand");
$calendar = JRequest::getVar('calendar');

$session =JFactory::getSession();
$theme_id =2;
/*$theme 	=JTable::getInstance('spidercalendar_theme', 'Table');
			// load the row from the db table
$theme->load( $theme_id);*/
			
	$categories=$this->categories;
	$cat_id = JRequest::getVar('cat_id');
$cat_ids = JRequest::getVar('cat_ids');

if($cat_ids=='')
$cat_ids .= $cat_id.',';
else
$cat_ids .= ','.$cat_id.',';

$cat_ids = substr($cat_ids, 0,-1);

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
$cat_ids_array = explode(',',$cat_ids);
if($cat_id!='')
{

if(getelementcountinarray($cat_ids_array,$cat_id )%2==0)
{
$index_in_line = getelementindexinarray($cat_ids_array, $cat_id);
$index_array = explode(',' , $index_in_line);
array_pop ($index_array);
for($j=0; $j<count($index_array); $j++)
unset($cat_ids_array[$index_array[$j]]);
$cat_ids = implode(',',$cat_ids_array);
}
}
else
$cat_ids = substr($cat_ids, 0,-1);
		


$bgid=$session->get('calendar_style');

//$weekstart=$session->get('weekstart');

$titlescloud=$session->get('titlescloud');

		$cal_width='700';
		 $bg_top = '#005478';
		 $bg_bottom = '#E1E1E1';
	 	 $border_color = '#005478';
		 $text_color_year = '#F9F2F4';
		 $text_color_month = '#F9F2F4';
		 $color_week_days = '#005D78';
		 $text_color_other_months = '#B0B0B0';
		 $text_color_this_month_unevented = '#6A6A6A';
		 $evented_color = '#6A6A6A';
		 $evented_color_bg = '#B4C5CC';
		 $color_arrow_year = '#CCD1D2';
		 $color_arrow_month = '#CCD1D2';
		 $sun_days = '#6A6A6A';
		 $event_title_color = '#236283';
		 $current_day_border_color = '#005478';
		 $cell_border_color = '#A9A9A9';
		 $cell_height = '70';
		 $popup_width = '800';
		 $popup_height = '500';
		 $number_of_shown_evetns = '2';
		 $sundays_font_size = '25';
		 $other_days_font_size= '25';
		 $weekdays_font_size= '25';
		 $border_width= '0';
		 $top_height='100';
		 $bg_color_other_months='#E1E1E1';
		 $sundays_bg_color='#E1E1E1';
		 $weekdays_bg_color='#D6D6D6';
		 $weekstart='su';
		 $weekday_sunday_bg_color='#B5B5B5';
		 $border_radius='0';
		 $border_radius2=$border_radius-$border_width;
		 $week_days_cell_height='50';
		 $year_font_size='25';
		 $month_font_size='35';
		 $arrow_size='45';
		 $arrow_size_hover=$arrow_size+5;
		 
		 $next_month_text_color = '#CCD1D2';
		 $prev_month_text_color = '#CCD1D2';
		 $next_month_arrow_color = '#CCD1D2';
		 $prev_month_arrow_color = '#1010A4';
		 $next_month_font_size ='16';
		 $prev_month_font_size = '16';
		 $month_type = '2';
		
		$date_bg_color = '#D6D4D5';
		$event_bg_color1 = '#E1E1E1';
		$event_bg_color2 = '#DEDCDD';
		$event_num_bg_color1 = '#005478';
		$event_num_bg_color2 = '#006E91';
		$event_num_color = '#FFFFFF';
		$date_font_size = '15';
		$event_num_font_size = '13';
		$event_table_height = '30';
		$date_height = '25';
		
		$day_month_font_size = '12';
		$week_font_size = '15';
		 $day_month_font_color = '#737373';
		$week_font_color = '#005476';		 
		 
		 
	
		
		 
		 $views_tabs_bg_color = '#01799C';
		 $views_tabs_text_color = '#FFFFFF';
		 $views_tabs_font_size = '13';	 
	 
			 
		 
		 
	 
		 
		 
		 
		 
		 
		 $bg=$session->get( 'border_color');
		
		 $bgborder=$session->get( 'bg_border_color');
		
		

		

		 $bg_color_selected=$session->get( 'bg_color_selected');

		
		 
	
		 



		


		 

		 $text_color_selected=$session->get( 'text_color_selected');

		 $border_day=$session->get( 'border_day');
		 
		 $calendar_width=$session->get( 'calendar_width');
		 
		  $calendar_bg=$session->get( 'calendar_bg');
		 
		 $titlescloud_text_color=$session->get( 'titlescloud_text_color');
		 
		 ///////////////////////////////////////////////////
		 $calendar_id = JRequest::getVar('calendar');
		 		 if($calendar_id=='')
		 $calendar_id=0;
		 if($cell_height=='')
		 $cell_height=70;
		 
		 if($cal_width=='')
		 $cal_width=700;
		
		
		
$db =JFactory::getDBO();

//$realtoday = getdate();
/*$uri = $_SERVER['HTTP_REFERER'];
$u =JURI::getInstance( $uri );*/
$date_REFERER=JRequest::getVar( "date",date("Y-m")); 
$year_REFERER=substr($date_REFERER,0,4); 
$month_REFERER=Month_name(substr( $date_REFERER,5,2)); 
$day_REFERER=substr( $date_REFERER,8,2); 



$date=JRequest::getVar( "date",date("Y-m")); 

$year=substr($date,0,4); 

$month=Month_name(substr( $date,5,2)); 

$day=substr( $date,8,2); 

echo '&nbsp;<style type="text/css">';
echo"

#bigcalendar".$id." table
{
border-collapse: initial;
border:0px;
max-width: none;
}
#bigcalendar".$id." table tr:hover td
{
background: none;

}
 #bigcalendar".$id." table td
 {
 padding: 0px;
vertical-align: none;
border-top:none;
line-height: none;
text-align: none;
 
 }
 
#bigcalendar".$id." p, ol, ul, dl, address
{
margin-bottom:0;

}
 

 #bigcalendar".$id." td,#bigcalendar".$id." tr,  #spiderCalendarTitlesList td,  #spiderCalendarTitlesList tr
 {
 border:none;
 }
#bigcalendar".$id." .general_table
{

border-radius: ".$border_radius."px;

}
#bigcalendar".$id." .top_table
 {

border-top-left-radius: ".$border_radius2."px;
border-top-right-radius: ".$border_radius2."px;

}
 #bigcalendar".$id." .cala_arrow a:link, #bigcalendar".$id." .cala_arrow a:visited {
	
	text-decoration:none;
	background:none;
	font-size:".$arrow_size."px;
}
#bigcalendar".$id." .cala_arrow a:hover {
	
	
	text-decoration:none;
	background:none;
}
#bigcalendar".$id." .cala_day a:link, #bigcalendar".$id." .cala_day a:visited {
	text-decoration:none;
	background:none;
	font-size:12px;
	color:red;
	
}


#bigcalendar".$id." .cala_day a:hover {
	
	text-decoration:none;
	background:none;
	
}
#bigcalendar".$id." .cala_day 
{

border:1px solid ".$cell_border_color.";
vertical-align:top;

}

#bigcalendar".$id." .weekdays
{

border:1px solid ".$cell_border_color.";


}
#bigcalendar".$id." .week_days
{
font-size:".$weekdays_font_size."px;
}


#bigcalendar".$id." .calyear_table {
	border-spacing:0;
	width:100%;
}
#bigcalendar".$id." .calmonth_table {	
	border-spacing:0;
	width:100%;
}
#bigcalendar".$id." .calbg, #bigcalendar".$id." .calbg td
{
	background-color:".$bg.";
	text-align:center;
	width:14%;
}
#bigcalendar".$id." .caltext_color_other_months 
{
	color:".$text_color_other_months.";
	border:1px solid ".$cell_border_color.";
	vertical-align:top;
	
}
#bigcalendar".$id." .caltext_color_this_month_unevented {
	color:".$text_color_this_month_unevented.";
}
#bigcalendar".$id." .calfont_year {
	font-family:".$session->get( 'calendar_font_year'.$id).";
	font-size:24px;
	font-weight:bold;
	color:".$text_color_year.";
}

#bigcalendar".$id." .calsun_days 
{
	color:".$sun_days.";
	border:1px solid ".$cell_border_color.";
	vertical-align:top;
	text-align:left;
	background-color:".$sundays_bg_color.";
	
}
#bigcalendar".$id." .calbottom_border
{

}

#bigcalendar".$id." .calborder_day
{
border: solid ".$border_day." 1px;
}

#spiderCalendarTitlesList
{
display:none; width:331px; margin:0px; padding:0px; border:none; z-index:99;position:fixed;  color:#".$titlescloud_text_color.";
}

#spiderCalendarTitlesList #sc1 
{
padding:0px; margin:0px; height:65px; background:url('".JURI::root(true)."/modules/mod_spidercalendar/images/TitleListBg1.png') no-repeat;
}
#spiderCalendarTitlesList #sc2
{
padding:0px; margin:0px; background:url('".JURI::root(true)."/modules/mod_spidercalendar/images/TitleListBg2.png') repeat-y;
}
#spiderCalendarTitlesList #sc3
{
padding:0px; margin:0px; height:32px; background:url('".JURI::root(true)."/modules/mod_spidercalendar/images/TitleListBg3.png') no-repeat;
}
#spiderCalendarTitlesList p
{
margin:20px;
margin-top:0px;
text-align:left;
}

#bigcalendar".$id." .views
{
float: right;
background-color: ".$views_tabs_bg_color.";
height: 25px;
width: 70px;
margin-right: 2px;
text-align: center;
cursor:pointer;
position: relative;
top: 5px;
}

#bigcalendar".$id." .views_select 
{

background-color: ".$views_tabs_bg_color.";
width: 120px;
text-align: center;
cursor: pointer;
padding: 6px;
position: relative;
}


#bigcalendar".$id." .drop_down_views
{
	list-style-type:none !important;
	position: absolute;
	top: 31px;
	left: -25px;
	display:none;
	z-index: 4545;
	
}

#bigcalendar".$id." .drop_down_views >li:hover .views_select, .drop_down_views >li.active .views_select
{
	background:".$bg_top.";
}

#bigcalendar".$id." .drop_down_views >li
{
	border-bottom:1px solid #fff !important;
}


#bigcalendar".$id." .views_tabs_select 
{
	display:none  !important;
}


@media only screen and (max-width : 640px) { 
 
#bigcalendar".$id." .views_tabs 
{
	display:none !important;
}

#bigcalendar".$id." .drop_down_views
{
	display:none ;
}

#bigcalendar".$id." .views_tabs_select
{
	display:block !important;
}


 
}


@media only screen and (max-width : 968px) { 
#bigcalendar".$id."  .cats >li
{
	float:none;
}
";

echo '</style>';



$view=JRequest::getVar('view');

$cell_width=$cal_width/7;


JHTML::_('behavior.modal'); 


$this_month = substr($year.'-'.add_0((Month_num($month))),5,2);
$prev_month=add_0((int)$this_month-1);
$next_month=add_0((int)$this_month+1);

$app = JFactory::getApplication('site');
$componentParams = $app->getParams('com_spidercalendar');

$defaultview=JRequest::getVar('def_view',$componentParams->get('defaultview'));

$viewselect=JRequest::getVar('views',$componentParams->get('viewselect'));

$views=explode(',',$viewselect);
array_pop($views);

$display='display:table';

if(count($views)==0)
{
$display="display:none";
echo '<style>
@media only screen and (max-width : 640px) { 
 
#views_tabs_select
{
	display:none !important;
}
}

</style>';
}
if(count($views)==1 and $views[0]==$defaultview)
{
$display="display:none";
echo '<style>
@media only screen and (max-width : 640px) { 
 
#views_tabs_select
{
	display:none !important;
}
}

</style>';
}
?>


<div id="afterbig<?php echo $id ?>">
<tr>
<td>
<div id="views_tabs" class="views_tabs" style="<?php echo $display ?>;width: 100%;">
<div class="views" style="<?php if(!in_array('day',$views) AND $defaultview!='day' ) echo 'display:none;' ;if($view=='bigcalendarday') echo 'background-color:'.$bg_top.';height:30px;top: 0' ?>" onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarday&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))).'-'.date('d') ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_DAY') ?></span></div>

<div class="views" style="<?php if(!in_array('week',$views) AND $defaultview!='week' ) echo 'display:none;' ;if($view=='bigcalendarweek') echo 'background-color:'.$bg_top.';height:30px;top: 0' ?>" onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarweek&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&months='.$prev_month.','.$this_month.','.$next_month.'&date='.$year.'-'.add_0((Month_num($month))).'-'.date('d') ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_WEEK') ?></span></div>

<div class="views" style="<?php if(!in_array('list',$views) AND $defaultview!='list' ) echo 'display:none;' ; if($view=='bigcalendarlist') echo 'background-color:'.$bg_top.';height:30px;top: 0' ?>" onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))) ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_LIST') ?></span></div>

<div class="views" style="<?php if(!in_array('month',$views) AND $defaultview!='month' ) echo 'display:none;' ; if($view=='bigcalendar') echo 'background-color:'.$bg_top.';height:30px;top: 0' ?>" onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendar&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))) ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_MONTH') ?></span></div>
</div>
</td>
<div id="views_tabs_select" class="views_tabs_select" style="<?php echo $display ?>" >
<div  id="views_select" class="views_select" style="color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px">
<?php if($view=='bigcalendarday') echo JText::_('TAB_DAY'); ?>
<?php if($view=='bigcalendar') echo JText::_('TAB_MONTH'); ?>
<?php if($view=='bigcalendarweek') JText::_('TAB_WEEK'); ?>
<?php if($view=='bigcalendarlist') echo JText::_('TAB_LIST'); ?>
<span>►</span>
</div>
<ul id="drop_down_views" class="drop_down_views">
<li <?php if($view=='bigcalendarday'):?> class="active" <?php endif; ?>  style="<?php if(!in_array('day',$views) AND $defaultview!='day' ) echo 'display:none;' ; ?>"><div class="views_select"   onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarday&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))).'-'.date('d') ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_DAY') ?></span></div></li>

<li <?php if($view=='bigcalendarweek'):?> class="active" <?php endif; ?> style="<?php if(!in_array('week',$views) AND $defaultview!='week' ) echo 'display:none;' ; ?>" ><div class="views_select"  onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarweek&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&months='.$prev_month.','.$this_month.','.$next_month.'&date='.$year.'-'.add_0((Month_num($month))).'-'.date('d') ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_WEEK') ?></span></div></li>

<li <?php if($view=='bigcalendarlist'):?> class="active" <?php endif; ?> style="<?php if(!in_array('list',$views) AND $defaultview!='list' ) echo 'display:none;' ;?>"><div class="views_select"   onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))) ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_LIST') ?></span></div></li>

<li <?php if($view=='bigcalendar'):?> class="active" <?php endif; ?>  style="<?php if(!in_array('month',$views) AND $defaultview!='month' ) echo 'display:none;'; ?>"><div class="views_select"   onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendar&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month))) ;?>','<?php echo $id ?>','<?php echo $border_radius2 ?>')" ><span style="position:relative;top:25%;color:<?php echo $views_tabs_text_color ?>;font-size:<?php echo $views_tabs_font_size  ?>px"><?php echo JText::_('TAB_MONTH') ?></span></div></li>

</ul>
</div>
<table cellpadding="0" cellspacing="0"  class="general_table"  style="border-spacing:0; width:100%; border:<?php echo $border_color ?> solid <?php echo $border_width ?>px; margin:0; padding:0;background-color:<?php echo $bg_bottom; ?>;">

    <tr>

        <td width="100%" style=" padding:0; margin:0">

            
              <table  cellpadding="0" cellspacing="0" border="0" style="border-spacing:0; font-size:12px; margin:0; padding:0; width:100%; "   >


                <tr  style="height:40px; width:100%;">
				 
				
				
                    <td class="top_table" align="center" colspan="7" style="background-image:url('components/com_spidercalendar/views/bigcalendar/images/Stver.png');padding:0; margin:0; background-color:<?php echo $bg_top ?>;height:20px; background-repeat: no-repeat;background-size: 100% 100%; " >

                        <?php //YEAR TABLE ?>

                   <table cellpadding="0" cellspacing="0" border="0" align="center" class="calyear_table"  style="margin:0; padding:0; text-align:center; width:100%; height:<?php echo $top_height ?>px;">

                         
								<tr>
								
								
								
								<td style="width:100%;vertical-align:center">
								<table style="width:100%;line-height:150%">
								<tr>
								
								<td width="15%">
								<div onclick="javascript:showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&def_view='.$defaultview.'&views='.$viewselect.'&view=bigcalendarlist&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.($year-1).'-'.add_0((Month_num($month))) ?>','<?php echo $id ?>')" style="cursor:pointer;width:100%; height:35px; background-color: rgba(0,0,0,0.3);"><span style="top: 25%;position: relative;font-size: 26px;color:<?php echo $bg_top ?>"><?php echo $year-1 ?></span></div>
								</td>
								
								
									<td class="cala_arrow" width="15%"  style="text-align:right;margin:0px;padding:0px">
									<a  style="text-shadow: 1px 1px 2px black;color:<?php echo $color_arrow_month ?>"
													href="javascript:showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php  

											if(Month_num($month)==1)
											echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.($year-1).'-12';
						
											else echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month)-1));

									?>','<?php echo $id ?>')">&#9668;</a>
									</td>
									<td style="text-align:center; margin:0;" width="40%" >

											<input type="hidden" name="month" readonly="" value="<?php echo $month?>"/>
										   <span  style="font-family:arial; color:<?php echo $text_color_month;?>; font-size:<?php echo $month_font_size ?>px;text-shadow: 1px 1px  black;"><?php echo $year.', '.JText::_($month)?></span>
										  
											
									</td>	
									<td style="margin:0; padding:0;text-align:left" width="15%"  class="cala_arrow"> 
									<a  style="text-shadow: 1px 1px 2px black;color:<?php echo $color_arrow_month ?>" href="javascript:showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php  

											if(Month_num($month)==12)
											echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.($year+1).'-01';
						
											else echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month)+1));

									?>','<?php echo $id ?>')">&#9658;</a>

									</td>
									
										<td width="15%">
								<div onclick="javascript:showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id=&cat_ids='.$cat_ids).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date='.($year+1).'-'.add_0((Month_num($month))) ?>','<?php echo $id ?>')" style="cursor:pointer;width:100%; height:35px; background-color: rgba(0,0,0,0.3);"><span style="top: 25%;position: relative;font-size: 26px;color:<?php echo $bg_top ?>"><?php echo $year+1 ?></span></div>
								</td>
													
									</tr>
									</table>
									</td>	
                            </tr>
							
                        </table>
					
                    </td>

			
                    <td colspan="7" style="margin:0; padding:0; background-color:<?php echo $bg_top ?>;" >

                        <?php //MONTH TABLE ?>

                    </td>

                </tr>

				</tr>
			
			
		<tr>	
			<td>
                <?php

//$today=$realtoday;
/*$document = &JFactory::getDocument();
   $document->addScript("media/system/js/modal.js");
   $document->addStyleSheet("media/system/css/modal.css");*/
   

function add_0($month_num)
{
if($month_num<10)
	return '0'.$month_num;
	return $month_num;
}

function Month_num($month_name)

{
	for( $month_num=1; $month_num<=12; $month_num++ )
	
	{  
	    if (date( "F", mktime(0, 0, 0, $month_num, 1, 0 ) ) == $month_name)
	    
	    {  
		return $month_num;  
		
	    } 
	     
	}
	
};

function Month_name($month_num)

{

    $timestamp = mktime(0, 0, 0, $month_num, 1, 2005);
 
    return date("F", $timestamp); 
    
};

$month_first_weekday = date("N", mktime(0, 0, 0, Month_num($month), 1, $year));
if($weekstart=="su"){
$month_first_weekday++;
if($month_first_weekday==8)
$month_first_weekday=1;
}

$month_days = date("t", mktime(0, 0, 0, Month_num($month), 1, $year));

$last_month_days = date("t", mktime(0, 0, 0, Month_num($month)-1, 1, $year));

$weekday_i=$month_first_weekday;

$last_month_days=$last_month_days-$weekday_i+2;

$percent=1;

$sum=$month_days-8+$month_first_weekday;

if($sum % 7 <> 0)

$percent = $percent + 1;

$sum = $sum - ( $sum % 7 );

$percent = $percent + ( $sum / 7 );

$percent=107/$percent;

$array_days=$this->array_days;
$array_days1=$this->array_days1;
$title=$this->title;
$ev_ids=$this->ev_ids;

$session->set('ev_ids',$ev_ids);






sort($array_days,SORT_NUMERIC );




/*for($i=1; $i<=$month_days; $i++)

{

if(isset($title[$i]))
{
$ev_title=explode('</p>',$title[$i]);
array_pop($ev_title);
$k=count($ev_title);
////
$ev_id=explode('<br>',$ev_ids[$i]);
array_pop($ev_id);
$ev_ids_inline=implode(',' , $ev_id);



}

}*/
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


if(!$array_days)
{
echo '<table  style="height:'.$event_table_height.'px;border-spacing:0;border-spacing:0;width: 100%;background-color:'.$event_bg_color1.'"><tr><td style="font-size:22px;font-weight:bold;width:15px;text-align:center;background-color:'.$event_num_bg_color1.';color:'.$event_num_color.'"></td><td ><h1 style="color:'.$event_title_color.'; border:none;font-size: 15px;padding: 0;margin: 0;">&nbsp;'.JText::_('THERE_IS_NO_EVENT_IN_THIS_MONTH').'</h1> </td></tr></table>';

}

for($i=0;$i<count($array_days);$i++)
{


$week_day=date('D',mktime(0, 0, 0, Month_num($month),$array_days[$i] , $year));

echo '<table style="width:100%;border-spacing:0;"><tr>
	<td style="height:'.$date_height.'px;font-size:'.$date_font_size.'px; padding-left:10px;background-color:'.$date_bg_color.'; color:#6E7276">';
	echo '<span style="font-size:'.$week_font_size .'px;color:'.$week_font_color.'">'.week_convert($week_day).'</span> <span style="font-size:'.$day_month_font_size .'px;color:'.$day_month_font_color .'">('.add_0($array_days[$i]).' '.JText::_($month).')</span>
	</td>
	
	</tr>';
	echo '<tr><td>';




foreach($title as $key=>$value)
{


if($key==$array_days[$i])
{

$ev_id=explode('<br>',$ev_ids[$key]);

array_pop($ev_id);

$ev_ids_inline=implode(',' , $ev_id);

$ev_title=explode('</p>',$value) ;

array_pop($ev_title);
 $db = JFactory::getDBO();

for($j=0;$j<count($ev_title);$j++)
{
          
	       $query = "SELECT #__spidercalendar_event_category.color AS color FROM #__spidercalendar_event  JOIN #__spidercalendar_event_category
	       ON #__spidercalendar_event.category=#__spidercalendar_event_category.id WHERE #__spidercalendar_event.calendar=".$calendar." AND 
	       #__spidercalendar_event.published='1' AND #__spidercalendar_event_category.published='1' AND #__spidercalendar_event.id=".$ev_id[$j];

	       $db->setQuery( $query );
	       $cat_color = $db->loadResult();
		   
		   
		   
if(($j+1)%2==0)
				{
				$color=$event_num_bg_color2;
				$table_color=$event_bg_color2;
				}
				else
				{
				$color=$event_num_bg_color1;
				$table_color=$event_bg_color1;
				}
				if( $cat_color!='')
					echo '<table class="last_table" style="overflow:hidden;height:'.$event_table_height.'px;border-spacing:0;width: 100%;background-color:'.$table_color.'"><tr><td style="font-size:'.$event_num_font_size.'px;font-weight:bold;width:15px;text-align:center;background-color:#'.$cat_color.';color:'.$event_num_color.'">'.($j+1).'</td><td  ><a class="modal'.$id.'"   rel="{handler: \'iframe\', size: {x: '.$popup_width.', y: '.$popup_height.'}}" style="text-decoration:none;font-size:13px;background:none;color:'.$event_title_color.'; " href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='.$theme_id.'&calendar_id='.$calendar_id.'&ev_ids='.$ev_ids_inline.'&eventID='.$ev_id[$j].'&date='.$year.'-'.add_0(Month_num($month)).'-'.$array_days[$i]).'&tmpl=component&Itemid='.$Itemid.'" ><b>'.$ev_title[$j].'</b></a></td></tr></table>';
				else
					echo '<table class="last_table" style="overflow:hidden;height:'.$event_table_height.'px;border-spacing:0;width: 100%;background-color:'.$table_color.'"><tr><td style="font-size:'.$event_num_font_size.'px;font-weight:bold;width:15px;text-align:center;background-color:'.$color.';color:'.$event_num_color.'">'.($j+1).'</td><td  ><a class="modal'.$id.'"   rel="{handler: \'iframe\', size: {x: '.$popup_width.', y: '.$popup_height.'}}" style="text-decoration:none;font-size:13px;background:none;color:'.$event_title_color.'; " href="'.JRoute::_('index.php?option=com_spidercalendar&view=spidercalendarbig&theme_id='.$theme_id.'&calendar_id='.$calendar_id.'&ev_ids='.$ev_ids_inline.'&eventID='.$ev_id[$j].'&date='.$year.'-'.add_0(Month_num($month)).'-'.$array_days[$i]).'&tmpl=component&Itemid='.$Itemid.'" ><b>'.$ev_title[$j].'</b></a></td></tr></table>';

}

}

}
echo '</td></tr></table>';

}

			


?>
</td>
</tr>
				</table>
			</tr>
</table>
</td>
</tr>
</table>
<?php 
	echo '<style>


	#gag p
		{
			display:inline;
		}

	#gag p:first-child
		{
			padding-left: 4px;
			padding-right: 2px;
			font-size: 18px;
			color:white!important;
		}
	#gag p:last-child
		{
			text-shadow:0px 0px white !important; 
			position:relative;
			top: -1px;
			left: -3px;
		}
		
		#first_letter
		{
			color:#fff;
			position:relative;
			top: -1px;
			font-size:12px !important;
		
		}
		
	.categories1 , .categories2
		{
			display:inline-block;
		}

		.categories2
		{
			position:relative;
			left: -9px;
			cursor:pointer;
		}
		.categories2:first-letter
		{
			color:#fff;
			
		}
		
		</style>';
//reindex cat_ids_array
$re_cat_ids_array = array_values($cat_ids_array);

for($i=0; $i<count($re_cat_ids_array); $i++)
{
echo'
<style>
#category'.$re_cat_ids_array[$i].'{
text-decoration:underline;
cursor:pointer;
}
</style>';

}

	if($cat_ids=='')
		$cat_ids='';

echo '<ul id="cats" style="list-style-type:none;padding-top: 5px;">';

foreach($categories as $category)
{
	
?>

<li style="float:left;"><p class="categories1" style="background-color:#<?php echo $category->color;?>">&nbsp;&nbsp;&nbsp;&nbsp;</p><p class="categories2" id="category<?php echo $category->id ?>" style="color:#<?php echo $category->color?>" onclick="showbigcalendar( 'bigcalendar<?php echo $id ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$id.'&theme_id='.$theme_id.'&calendar='.$calendar_id.'&cat_id='.$category->id.'&cat_ids='.$cat_ids ).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$year.'-'.add_0((Month_num($month)))?>','<?php echo $id ?>')" > <?php echo  $category->title ?></p></li>

<?php


}

echo '</ul><br><br>';

$db		=JFactory::getDBO();
$query = "SELECT * FROM #__spidercalendar_calendar where id=".$calendar_id."";
	$db->setQuery( $query );
	
	$calendar = $db->loadObjectList();


$user =JFactory::getUser();

$userGroups = $user->get('groups');


if(isset($calendar[0]))
{
$access=explode(',',$calendar[0]->gid);
$userGroups=array_merge(array(),$userGroups);
if(!$userGroups)
$userGroups=array(1);
foreach($userGroups as $key=>$value)
{

if (in_array($userGroups[$key],$access) ){
echo '<a href="index.php?option=com_spidercalendar&view=add_event&calendar='.$calendar_id.'&module_id='.$id.'">'.JText::_('ADD_EVENT').'</a> 
<a style="float:right" href="index.php?option=com_spidercalendar&view=show_events&calendar='.$calendar_id.'&module_id='.$id.'">'.JText::_('MANAGE_EVENTS').'</a>';
break;
}
}
}
