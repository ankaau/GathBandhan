<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
$app = JFactory::getApplication('site');
$componentParams = $app->getParams('com_spidercalendar');
$calendar = JRequest::getVar('calendar',$componentParams->get('calendar'));
$theme_id = JRequest::getVar('theme',$componentParams->get('theme_id'));
if($theme_id=='')
$theme_id=13;
$document = JFactory::getDocument();
$document->addScript('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
$document->addStyleSheet(JURI::root().'/components/com_spidercalendar/views/responsive.css','text/css',"screen");
//$document->addScript(JURI::root().'/components/com_spidercalendar/views/responsive.js');
$defaultview=JRequest::getVar('def_view',$componentParams->get('defaultview'));
$viewselect=JRequest::getVar('views',$componentParams->get('viewselect'));
if($defaultview=="")
$defaultview="month";

if($viewselect=="")
$viewselect="month,list,day,week,";


$views=explode(',',$viewselect);
array_pop($views);

$theme 	=JTable::getInstance('spidercalendar_theme', 'Table');
			// load the row from the db table
$theme->load( $theme_id);
$cal_width='700';
		 $popup_width = '800';
		 $popup_height ='500';

$border_radius=$theme->border_radius-$theme->border_width;
$rand=JRequest::getVar('rand');
JHTML::_('behavior.modal'); 

function add_0($month_num)
{
if($month_num<10)
	return '0'.$month_num;
	return $month_num;
}

$this_month = substr($this->date,5,2);
$prev_month=add_0((int)$this_month-1);
$next_month=add_0((int)$this_month+1);


?>

<input type="hidden" value="<?php echo $cal_width ?>" id="cal_wdth<?php echo $rand ?>" />

<div id='bigcalendar<?php echo $rand ?>'></div>

<script>

var randi;
function showbigcalendar(id,calendarlink, randi)
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

xmlHttp.open("GET",calendarlink,false);
xmlHttp.send();
//alert(document.getElementById('days').parentNode.lastChild.childNodes[6].innerHTML);

 jQuery(document).ready(function (){
 


    jQuery('#views_select').toggle(function () {
	
    jQuery('#drop_down_views').stop(true, true).delay(200).slideDown(500);
  }, function () {
  
    jQuery('#drop_down_views').stop(true, true).slideUp(500);
	
  });
  
  if(jQuery(window).width() > 640 )
  {
	
	jQuery('drop_down_views').hide();
	var parent_width = document.getElementById('bigcalendar<?php echo $rand ?>').parentNode.clientWidth;
var responsive_width = (<?php echo $cal_width ?>)/parent_width*100;
document.getElementById('afterbig<?php echo $rand ?>').style.width=responsive_width+'%';


  }
else
{
document.getElementById('afterbig<?php echo $rand ?>').style.width='100%';

}
	
	});
if(document.getElementById('days'))
{
document.getElementById('days').parentNode.lastChild.childNodes[6].style.borderBottomRightRadius='<?php echo $border_radius ?>px';
document.getElementById('days').parentNode.lastChild.childNodes[0].style.borderBottomLeftRadius='<?php echo $border_radius ?>px';	
}

	

		window.addEvent('domready', function() {
			SqueezeBox.initialize({});
			SqueezeBox.assign($$('a.modal'+randi), {				
				parse: 'rel'
			});
			
		
		});
		

  window.addEvent('domready', function() {
			$$('.hasTip').each(function(el) {
				var title = el.get('title');
				if (title) {
					var parts = title.split('::', 2);
					el.store('tip:title', parts[0]);
					el.store('tip:text', parts[1]);
				}
			});
			var JTooltips = new Tips($$('.hasTip'), { maxTitleChars: 50, fixed: false});
		});
  pp_size();
};

 document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {
     
		window.parent.SqueezeBox.close();
		
    }
}; 
//////////////////////////////////POP-UP RESPONSIVE 
jQuery.fn.scr_center = function () {
    this.css("position","absolute");
    this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + 
                                                $(window).scrollTop()) + "px");
    this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + 
                                                $(window).scrollLeft()) + "px");
    return this;
}

jQuery(window).resize(function(){
	jQuery('#sbox-window').scr_center();
	width_orig=<?php echo  $popup_width ?>;
	height_orig=<?php echo  $popup_height ?>;
		
		if($(window).width()<640)
			{
				width=$(window).width()-100;
				height=$(window).height()-100
				jQuery('.modal<?php echo $rand ?>').attr('rel',"{handler: 'iframe', size: {x: "+width+", y: "+height+"}}")
				jQuery('#sbox-window').css('width',width+'px')
				jQuery('#sbox-window').css('height',height+'px')
				jQuery('#sbox-content iframe').css('width',width+'px');
				jQuery('#sbox-content iframe').css('height',height+'px');
			}
		else
			{
				jQuery('.modal<?php echo $rand ?>').attr('rel',"{handler: 'iframe', size: {x: "+width_orig+", y: "+height_orig+"}}")
				jQuery('#sbox-window').css('width',width_orig+'px')
				jQuery('#sbox-window').css('height',height_orig+'px')
				jQuery('#sbox-content iframe').css('width',width_orig+'px');
				jQuery('#sbox-content iframe').css('height',height_orig+'px');			
			
			}



})
function pp_size(){
if(jQuery(window).width()<640)
			{
				width=jQuery(window).width()-100;
				height=jQuery(window).height()-100
				jQuery('.modal<?php echo $rand ?>').attr('rel',"{handler: 'iframe', size: {x: "+width+", y: "+height+"}}")
				jQuery('#sbox-window').css('width',width+'px')
				jQuery('#sbox-window').css('height',height+'px')
				jQuery('#sbox-content iframe').css('width',width+'px');
				jQuery('#sbox-content iframe').css('height',height+'px');
				jQuery('#sbox-overlay').css('width','100%');
	
	}
}
/////////////////////////////////////
SqueezeBox.presets.onClose=function (){window.parent.document.getElementById('sbox-content').innerHTML="";};

if('<?php echo $defaultview  ?>'=='month')
{
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendar&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$rand.'&theme_id='.$theme_id.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$this->date ;?>','<?php echo $rand ?>')

}

if('<?php echo $defaultview  ?>'=='list')
{
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$rand.'&theme_id='.$theme_id.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$this->date ;?>','<?php echo $rand ?>')

}

if('<?php echo $defaultview  ?>'=='week')
{
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarweek&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$rand.'&theme_id='.$theme_id.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&months='.$prev_month.','.$this_month.','.$next_month.'&date='.$this->date.'-'.date('d') ;?>','<?php echo $rand ?>')
}

if('<?php echo $defaultview  ?>'=='day')
{
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarday&def_view='.$defaultview.'&views='.$viewselect.'&rand='.$rand.'&theme_id='.$theme_id.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$this->date.'-'.date('d') ;?>','<?php echo $rand ?>')
}


</script>
<?php 

?>