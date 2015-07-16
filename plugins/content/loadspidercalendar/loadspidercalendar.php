<?php



 /**

 * @package Spider Calendar lite

 * @author Web-Dorado

 * @copyright (C) 2012 Web-Dorado. All rights reserved.

 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

 **/

 

// No direct access allowed to this file

defined( '_JEXEC' ) or die( 'Restricted access' );

 

// Import Joomla! Plugin library file

jimport('joomla.plugin.plugin');

jimport('joomla.filesystem.file');

jimport('joomla.filesystem.folder');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_spidervideoplayer/tables');
JHTML::_('behavior.modal'); 

/*

$lang = & JFactory::getLanguage();

$lang->load('com_spidervideoplayer',JPATH_BASE);

*/

 

class plgContentLoadspidercalendar extends JPlugin

{

	/**

	* Plugin that loads module positions within content

	*/

// onPrepareContent, meaning the plugin is rendered at the first stage in preparing content for output

	public function onContentPrepare($context, &$row, &$params, $page=0 )

	{

      

	    // A database connection is created

		$db = JFactory::getDBO();

		// simple performance check to determine whether bot should process further

		if ( JString::strpos( $row->text, 'loadspidercalendar' ) === false ) {

			return true;

		}

	 	// expression to search for

	 	$regex = '/{loadspidercalendar\scalendar=*.*?}/i';

 

		// check whether plugin has been unpublished

		if ( !$this->params->get( 'enabled', 1 ) ) {

			$row->text = preg_replace( $regex, '', $row->text );

			return true;

		}

 

	 	// find all instances of plugin and put in $matches

		preg_match_all( $regex, $row->text, $matches );

		//print_r($matches);

		// Number of plugins

	 	$count = count( $matches[0] );

	 	// plugin only processes if there are any instances of the plugin in the text

	 	if ( $count ) {

			// Get plugin parameters

	 		$this->_process( $row, $matches, $count, $regex );

		}

		// No return value

	}

// The proccessing function

	protected function _process( &$row, &$matches, $count, $regex )

	{

		$style=-1;

		 
		        ob_start();

		
	$content_js='	
		
function showbigcalendar(id,calendarlink,randi)
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
	
		window.addEvent("domready", function() {
			SqueezeBox.initialize({});
			SqueezeBox.assign($$("a.modal"+randi), {				
				parse: "rel"
			});
			
		
		});
		
		
 		jQuery(document).ready(function (){
 		    jQuery("#bigcalendar"+randi+" .views_select").toggle(function () {
	
    jQuery("#bigcalendar"+randi+" .drop_down_views").stop(true, true).delay(200).slideDown(500);
  }, function () {
  
    jQuery("#bigcalendar"+randi+" .drop_down_views").stop(true, true).slideUp(500);
	
  });
  
  if(jQuery(window).width() > 640 )
  {
	
	jQuery("#bigcalendar"+randi+" .drop_down_views").hide();
	   var parent_width = document.getElementById("bigcalendar"+randi).parentNode.clientWidth;
   var cal_wdt=document.getElementById("cal_wdth"+randi).value;
var responsive_width = (cal_wdt)/parent_width*100;
document.getElementById("afterbig"+randi).style.width=responsive_width+"%";

	
  }
else
{
document.getElementById("afterbig"+randi).style.width="100%";

}
	
	});
  
 pp_size()  ;
};

 document.onkeydown = function(evt) {
    evt = evt || window.event;
    if (evt.keyCode == 27) {
     
		window.parent.SqueezeBox.close();
		
    }
}; 

SqueezeBox.presets.onClose=function (){window.parent.document.getElementById("sbox-content").innerHTML="";};		
		
		
		
	';	
		
		
		$doc =& JFactory::getDocument();
		$doc->addScriptDeclaration( $content_js );	
		
		
		
	 	for ( $i=0; $i < $count; $i++ )

		{

	 		$load = str_replace( 'loadspidercalendar', '', $matches[0][$i] );

	 		$load = str_replace( '{', '', $load );

	 		$load = str_replace( '}', '', $load );

 			$load = trim( $load );

			$params=explode(' ',$load);

			$calendar=explode('=',$params[0]);


			$view=explode('=',$params[1]);
			$views=explode('=',$params[2]);


			if($calendar[0]!='calendar' || $view[0]!='view' || $views[0]!='views' )

				continue;

			$modules	= $this->_load( $calendar[1], 2,$view[1],$views[1]);

			$row->text 	= preg_replace( '{'. $matches[0][$i] .'}', $modules, $row->text );

	 	}

 

	  	// removes tags without matching module positions

		$row->text = preg_replace( $regex, '', $row->text );

	}

// The function who takes care for the 'completing' of the plugins' actions : loading the module(s)

	protected function _load( $calendar, $theme,$view,$views)

	{

        ob_start();

        static $embedded;

                if(!$embedded)

        {

            $embedded=true;

        }

		
		$db =& JFactory::getDBO();

		$query ="SELECT * FROM #__spidercalendar_theme WHERE id=".(int)$theme ;

		$db->setQuery($query); 

		$param = $db->loadObject();
	

$query ="SELECT * FROM #__spidercalendar_calendar WHERE id=".(int)$calendar ;

		$db->setQuery($query); 

		$calendar_param = $db->loadObject();

if((int)$calendar_param->def_month<10)
$month='0'.(int)$calendar_param->def_month;
else
$month=(int)$calendar_param->def_month;
	
	
	if($calendar_param->def_year!='' and $calendar_param->def_month!='' )
	$dateMonth=$calendar_param->def_year.'-'.$month.'-'.date("d");
	else
	$dateMonth=date("Y").'-'.date("m");	
	
	
	
	$date=date("Y").'-'.date("m").'-'.date("d");	
	
	
	
	$d = new DateTime($date);$this_month = $d->format('m');
			$d->modify("-1 month");
			$prev_month = $d->format('m');
			$d->modify("+2 month");
			$next_month=$d->format('m');
			$months=$prev_month.','.$this_month.','.$next_month;			
			$width = $param->width;
$height= 5*(int)$param->cell_height+(int)$param->top_height+(int)$param->week_days_cell_height+(int)$param->border_width;


$popup_width = $param->popup_width;
		 $popup_height = $param->popup_height;

$rand =rand();

$jss='
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
	jQuery(\'#sbox-window\').scr_center();
	width_orig='.$popup_width.';
	height_orig='.$popup_height.';
		
		if($(window).width()<640)
			{
				width=$(window).width()-100;
				height=$(window).height()-100
				jQuery(\'.modal'.$rand.'\').attr(\'rel\',"{handler: \'iframe\', size: {x: "+width+", y: "+height+"}}")
				jQuery(\'#sbox-window\').css(\'width\',width+\'px\')
				jQuery(\'#sbox-window\').css(\'height\',height+\'px\')
				jQuery(\'#sbox-content iframe\').css(\'width\',width+\'px\');
				jQuery(\'#sbox-content iframe\').css(\'height\',height+\'px\');
			}
		else
			{
				jQuery(\'.modal'.$rand.'\').attr(\'rel\',"{handler: \'iframe\', size: {x: "+width_orig+", y: "+height_orig+"}}")
				jQuery(\'#sbox-window\').css(\'width\',width_orig+\'px\')
				jQuery(\'#sbox-window\').css(\'height\',height_orig+\'px\')
				jQuery(\'#sbox-content iframe\').css(\'width\',width_orig+\'px\');
				jQuery(\'#sbox-content iframe\').css(\'height\',height_orig+\'px\');			
			
			}



})

function pp_size()	{
		if(jQuery(window).width()<640)
			{

				width=jQuery(window).width()-100;
				height=jQuery(window).height()-100
				jQuery(\'.modal'.$rand.'\').attr(\'rel\',"{handler: \'iframe\', size: {x: "+width+", y: "+height+"}}")
				jQuery(\'#sbox-window\').css(\'width\',width+\'px\')
				jQuery(\'#sbox-window\').css(\'height\',height+\'px\')
				jQuery(\'#sbox-content iframe\').css(\'width\',width+\'px\');
				jQuery(\'#sbox-content iframe\').css(\'height\',height+\'px\');
				jQuery(\'#sbox-overlay\').css(\'width\',\'100%\');
	
				}

			}
/////////////////////////////////////

';

		$doc =JFactory::getDocument();

		$doc->addScriptDeclaration( $jss );	




$border_radius=$param->border_radius-$param->border_width;


//echo $theme;
?>
<input type="hidden" value="<?php echo $width ?>" id="cal_wdth<?php echo $rand ?>" />

<div id='bigcalendar<?php echo $rand ?>'></div>

<script>

<?php if($view=='month'){ ?>
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendar&def_view='.$view.'&views='.$views.'&rand='.$rand.'&theme_id='.$theme.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$dateMonth ;?>','<?php echo $rand ?>')

 document.getElementById('bigcalendar<?php echo $rand ?>').getElementById('days').parentNode.lastChild.childNodes[6].style.borderBottomRightRadius='<?php echo $border_radius ?>px';
 document.getElementById('bigcalendar<?php echo $rand ?>').getElementById('days').parentNode.lastChild.childNodes[0].style.borderBottomLeftRadius='<?php echo $border_radius ?>px';	
<?php } ?>

<?php if($view=='day'){ ?>
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarday&def_view='.$view.'&views='.$views.'&rand='.$rand.'&theme_id='.$theme.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$date ;?>','<?php echo $rand ?>')
<?php } ?>

<?php if($view=='list'){ ?>
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarlist&def_view='.$view.'&views='.$views.'&rand='.$rand.'&theme_id='.$theme.'&calendar='.$calendar).'&format=row&tmpl=component&Itemid='.JRequest::getVar( "Itemid","").'&date='.$date ;?>','<?php echo $rand ?>')
<?php } ?>

<?php if($view=='week'){ ?>
showbigcalendar( 'bigcalendar<?php echo $rand ?>','<?php echo JRoute::_('index.php?option=com_spidercalendar&view=bigcalendarweek&def_view='.$view.'&views='.$views.'&rand='.$rand.'&theme_id='.$theme.'&calendar='.$calendar).'&format=row&tmpl=component&months='.$months.'&Itemid='.JRequest::getVar( "Itemid","").'&date='.$date ;?>','<?php echo $rand ?>')
<?php } ?>
 
</script>

<?php
        $content=ob_get_contents();

                ob_end_clean();

                return $content;



	}

}

?>