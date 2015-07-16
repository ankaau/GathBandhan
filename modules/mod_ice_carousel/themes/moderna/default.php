<?php
/**
 * IceAccordion Extension for Joomla 3.0 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2012 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/iceaccordion.html
 * @Support 	http://www.icetheme.com/Forums/IceCarousel/
 *
 */
 

/* no direct access*/
defined('_JEXEC') or die;


$icecarousel_caption_width = $params->get('main_width',"240");

?>

<style type="text/css">

#icecarousel<?php echo $module->id;?> .slides > div { width:<?php echo $item_width ;?>px; float:left;}

</style>


<div id="icecarousel<?php echo $module->id;?>" class="icecarousel flexslider moderna-theme">
        
        
	<div class="slides">
        
		<?php
        foreach($list as $key=>$item){
            $activeclass = "";
            if($key == 0){
                $activeclass = "active";
            }
            ?>
            
            
            <div>
                    
                    	
                        <div class="icecarousel-inner">
					
							<?php if ($params->get('link_titles', 1)) : ?>
                                <a href="<?php echo $item->link; ?>">  	
                                <?php if($item->mainImage): ?>
                                    <?php echo $item->mainImage; ?>
                                <?php endif; ?>
                                </a>
                             <?php
                                else:
                                         echo $item->mainImage;
                                endif;
                              ?>	
						
							<?php if($params->get("display_caption", 1)): ?>	
						
							<div class="icecarousel-caption">
							  <?php if ($params->get('show_title', 1)) : ?>
                                  <h4>
                                  <?php if ($params->get('link_titles') == 1) : ?>
                                    <a class="icecarousel-title" href="<?php echo $item->link; ?>">
                                    <?php echo $item->title; ?></a>
                                  <?php
                                    else:
                                        echo $item->title;
                                    endif;
                                  ?>
                                  </h4>
                              <?php endif; ?>
							  
                              <?php if ($params->get('show_description') == 1) : ?>
								  <div class="icecarousel-description">

										<p><?php echo $item->displayIntrotext; ?></p>

								  </div>
                              <?php endif; ?>
								
							  <?php if ($params->get('show_readmore')) :?>
								<p class="mod-articles-category-readmore">
									<a class="mod-articles-category-title" href="<?php echo $item->link; ?>">
									<?php 
											echo JText::_('MOD_CAROSUEL_READ_MORE');
									?>
								</a>
								</p>
							  <?php endif; ?>
                              
							  
							</div>
							<?php	endif; ?>
                            
                    
                    </div>        
						
                        
          </div>
         <?php }?>
                    
                    
	</div>
        
</div>

<script type="text/javascript">
jQuery(document).ready(function() {   

	jQuery(".moderna-theme .slides").hover(function () {
		jQuery(".moderna-theme .icecarousel-inner").css({opacity : .25});
	  }, 
	  function () {
		jQuery(".moderna-theme .icecarousel-inner").css({ opacity : 1});
	  }
	);
	
}); 
</script>


