<?php
/**
 * IceCarosuel Extension for Joomla 3.0 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/icecarosuel.html
 * @Support 	http://www.icetheme.com/Forums/IceCarosuel/
 *
 */

/* no direct access*/
defined('_JEXEC') or die;
if(!defined("DS")){
	define("DS", DIRECTORY_SEPARATOR);
}
if( !defined('PhpThumbFactoryLoaded') ) {
  require_once dirname(__FILE__).DS.'libs'.DS.'phpthumb'.DS.'ThumbLib.inc.php';
  define('PhpThumbFactoryLoaded',1);
}

// Include the syndicate functions only once
require_once dirname(__FILE__).DS.'helper.php';

$list = modIceCarousel::getList( $params );

$themeClass 								= $params->get( 'theme' , '');
$openTarget 								= $params->get( 'open_target', 'parent' );
$class 										= !$params->get( 'navigator_pos', 0 ) ? '':'ice-'.$params->get( 'navigator_pos', 0 );
$theme		   							    =  $params->get( 'theme', '' );
$target 									= 'target="'.$params->get('open_target','_parent').'"';
$style           							= $params->get('style', 'default');
$isThumb       								= $params->get( 'auto_renderthumb',1);
$itemContent								= $isThumb==1?'desc-image':'introtext';
$main_width   								= $params->get('main_width', '200');
$item_width   								= $params->get('item_width', '200');
$image_height   							= $params->get('main_height', '200');


$slideshowspeed   							= $params->get('slideshowspeed', '7000');
$animationspeed   							= $params->get('animationspeed', '600');

$istruncate       							= $params->get( 'istruncate',0);

/*Paging*/
$maxPages									= (int)$params->get( 'max_items_per_page', 3 );
$pages 										= array_chunk( $list, $maxPages  );
$totalPages 								= count($pages);

// calculate width of each row.
$item_heading 								= $params->get('item_heading',"3");
$auto_start 								= $params->get("auto_start", 1);
$item_layout 								= "_items";

/*End Paging*/
$itemLayoutPath 							= modIceCarousel::getLayoutByTheme($module, $theme, $item_layout);

// load custom theme
	if( $theme && $theme != -1 ) {
		require( modIceCarousel::getLayoutByTheme($module, $theme) );
	} 
	else {
		require( JModuleHelper::getLayoutPath($module->module) );
	}
modIceCarousel::loadMediaFiles( $params, $module, $theme );
?>
    


<script type="text/javascript">
// Can also be used with $(document).ready()

(function($) {
	$(window).load(function(){
		$('#icecarousel<?php echo $module->id;?>').flexslider({
		selector: ".slides > div", 
		animation: "slide",
		direction: "horizontal",
		itemWidth:<?php echo $item_width ;?>,
		slideshowSpeed:<?php echo $slideshowspeed ;?>, 
		animationspeed:<?php echo $animationspeed ;?>,  
		itemMargin:0,
		minItems:1,
		maxItems:0, 
		move: 0,    
		
		<?php if ($params->get('auto_start') == 1) : ?>
		slideshow: true, 
		<?php else: ?>
		slideshow: false,
		<?php endif; ?>
		
		<?php if ($params->get('direction_arrow') == 1) : ?>
		directionNav: true,
		<?php else: ?>
		directionNav: false,
		<?php endif; ?>
		
		<?php if ($params->get('nav_bullets') == 1) : ?>
		controlNav: true,
		<?php else: ?>
		controlNav: false,
		<?php endif; ?>
		
        start: function(slider){
          $('body').removeClass('loading');
        }
      });
    });
   })(jQuery);

</script>

