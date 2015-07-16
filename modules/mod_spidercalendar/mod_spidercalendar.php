<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
$session =& JFactory::getSession();

$id= $module->id;


$session->set('calendar_style'.$id, $params->get('calendar_style'));

$session->set('border_color'.$id, "#".$params->get('border_color'));

$session->set('bg_border_color'.$id, "#".$params->get('bg_border_color'));

$session->set('arrow_color'.$id, "#".$params->get('arrow_color'));

$session->set('text_color_week_days'.$id, "#".$params->get('text_color_week_days'));

$session->set('bg_color_selected'.$id, "#".$params->get('bg_color_selected'));

$session->set('text_color_this_month_evented'.$id, "#".$params->get('text_color_this_month_evented'));
$session->set('bg_color_this_month_evented'.$id, "#".$params->get('bg_color_this_month_evented'));

$session->set('text_color_sun_days'.$id, "#".$params->get('text_color_sun_days'));

$session->set('text_color_other_months'.$id, "#".$params->get('text_color_other_months'));

$session->set('text_color_this_month_unevented'.$id, "#".$params->get('text_color_this_month_unevented'));

$session->set('text_color_year'.$id, "#".$params->get('text_color_year'));

$session->set('text_color_month'.$id, "#".$params->get('text_color_month'));

$session->set('text_color_selected'.$id, "#".$params->get('text_color_selected'));

$session->set('border_day'.$id, "#".$params->get('border_day'));

$session->set('calendar_font_day'.$id, $params->get('calendar_font_day'));

$session->set('calendar_font_month'.$id, $params->get('calendar_font_month'));

$session->set('calendar_font_year'.$id, $params->get('calendar_font_year'));

$session->set('calendar_font_weekday'.$id, $params->get('calendar_font_weekday'));

$session->set('weekstart'.$id, $params->get('weekstart'));

$session->set('titlescloud'.$id, $params->get('titlescloud'));

$session->set('calendar_width'.$id, $params->get('calendar_width'));

$session->set('title_color'.$id, "#".$params->get('title_color'));

$session->set('title_size'.$id, $params->get('title_size'));

$session->set('title_font'.$id, $params->get('title_font'));

$session->set('title_style'.$id, $params->get('title_style'));

$session->set('date_color'.$id, "#".$params->get('date_color'));

$session->set('date_size'.$id, $params->get('date_size'));

$session->set('date_font'.$id, $params->get('date_font'));

$session->set('date_style'.$id, $params->get('date_style'));

$session->set('date_format'.$id, $params->get('date_format'));

$session->set('like_button'.$id, $params->get('like_button'));

$session->set('calendar_bg'.$id, $params->get('calendar_bg'));

$session->set('titlescloud_text_color'.$id, $params->get('titlescloud_text_color'));

$session->set('show_time'.$id, $params->get('show_time'));

$session->set('show_repeat'.$id, $params->get('show_repeat'));


$session->set('select_menu_item'.$id, $params->get('select_menu_item'));

$session->set('weekdays_bg_color'.$id, "#".$params->get('weekdays_bg_color'));


$session->set('weekday_su_bg_color'.$id, "#".$params->get('weekday_su_bg_color'));

$session->set('cell_border_color'.$id, "#".$params->get('cell_border_color'));






if($params->get('default_year')>=1 )
$def_date=$params->get('default_year')."-";
else
$def_date=date("Y").'-';
if($params->get('default_month')>=1 and $params->get('default_month')<=12)
{

$default_month=$params->get('default_month')+0;
if($default_month>=10)
$def_date.=$default_month;
else
$def_date.="0".$default_month;
}
else
$def_date.=date("m");


$date=JRequest::getVar( "date".$id."",$def_date); 


$calendar = (int)$params->get('calendar');
?>

<div id="calendar_<?php echo $module->id ?>" style="margin:0; padding:0"></div>

<script type="text/javascript">


function showcalendar(id,calendarlink)
{
	
var xmlHttp;
	try{	
		xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}
		catch (e){
		    try{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				alert("No AJAX!?");
				return false;
			}
		}
	}

xmlHttp.onreadystatechange=function(){
	if(xmlHttp.readyState==4){
		document.getElementById(id).innerHTML=xmlHttp.responseText;
	}
}

xmlHttp.open("GET",calendarlink,true);
xmlHttp.send(null);

}


function showTitlesList(ev,text)
{
	getCursorXY(ev);
	document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>').innerHTML = '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td id="sc1">&nbsp;</td></tr><tr><td id="sc2">'+text+'</td></tr><tr><td id="sc3">&nbsp;</td></tr>';
	document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>').style.left=(tempX-33) + "px";
	document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>').style.top=(tempY+15) + "px"; 
	document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>').style.display = "block";
}


var tempX = 0;

var tempY = 0;

function getCursorXY(e) 
{
e = e || window.event;  
  
if (e.pageX || e.pageY) 
{        
tempX = e.pageX-(document.documentElement.scrollLeft ||document.body.scrollLeft);        
tempY = e.pageY-(document.documentElement.scrollTop  ||document.body.scrollTop);
}

else 
{        
tempX = e.clientX - document.documentElement.clientLeft;        
tempY = e.clientY - document.documentElement.clientTop;    
}

}


function hideTitlesList()
{	
	if(document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>')) document.getElementById('spiderCalendarTitlesList_<?php echo $id ?>').style.display = "none";
}

var oldFunctionOnLoad = null;
var oldFunctionOnScroll = null;
var siteRoot = '';
var module_id = '';

function AddToOnload<?php echo $id ?>()
{ 
	if(oldFunctionOnLoad && al2){al2=false; oldFunctionOnLoad(); }
	
	var spiderCalendarTitlesListElement = document.createElement('div');
	var spiderCalendarTitlesListId = document.createAttribute('id');
	spiderCalendarTitlesListId.nodeValue = 'spiderCalendarTitlesList_<?php echo $id ?>';
	spiderCalendarTitlesListElement.setAttributeNode(spiderCalendarTitlesListId);
	document.body.appendChild(spiderCalendarTitlesListElement);
	
	
	if (document.images) 
	{
    img1 = new Image();
    img1.src = siteRoot+'/modules/mod_spidercalendar/images/TitleListBg1.png';
    img2 = new Image();
    img2.src = siteRoot+'/modules/mod_spidercalendar/images/TitleListBg2.png';
    img3 = new Image();
    img3.src = siteRoot+'/modules/mod_spidercalendar/images/TitleListBg3.png';
	}
	
}




function AddToScroll<?php echo $id ?>()
{ 
	if(oldFunctionOnScroll && al1){al1=false; oldFunctionOnScroll(); }

	hideTitlesList();
}

function loadBody<?php echo $id ?>(sRoot)
{	siteRoot=sRoot;
	al1=true;
	al2=true;
	oldFunctionOnLoad = window.onload;
	oldFunctionOnScroll = window.onscroll;
	window.onload = AddToOnload<?php echo $id ?>;
	window.onscroll = AddToScroll<?php echo $id ?>;
}

function do_nothing()
{
}
	
	
	



showcalendar( 'calendar_<?php echo $module->id ?>','<?php echo str_replace("&amp;","&", JRoute::_('index.php?option=com_spidercalendar&view=AJAXcalendar&calendar='.$calendar.'&module_id='.$id).'&format=row&Itemid='.JRequest::getVar( "Itemid","").'&date'.$id.'='.$date) ?>');

		loadBody<?php echo $id ?>('<?php echo JURI::root(true) ?>');
</script>