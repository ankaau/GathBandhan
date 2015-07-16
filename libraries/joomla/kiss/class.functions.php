<?php
defined('_JEXEC') or die;

    /**
     * Base class with KISS Functions. Contains decoration elements like buttons, bars and images.
     */

if (!defined('_KISS_FUNCTIONS')) {

    define('_KISS_FUNCTIONS', 1);
	include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'kiss'.DS.'class.browser.php');
	if (version_compare(JVERSION, '3.0', 'ge')) {	
	// load KISS bootstrap CSS
	$document = JFactory::getDocument();	
	$document->addStyleSheet(DS.'libraries'.DS.'joomla'.DS.'kiss'.DS.'kiss-bootstrap.css');	
	} else if (version_compare(JVERSION, '2.5', 'ge')) {
	jimport('joomla.html.pane');	
	}

class KISSLook {

	var $browser = null;
	
	// Load KISS CSS files dependant on the browser being used
	public function load_css()
	{
	$document = JFactory::getDocument();
	$brw = new KSBrowser();
	$agent = $brw->detectBrowser();

	switch ($agent->browser) {
	  case 'ff':
	  default:
	  $document->addStyleSheet(DS.'libraries'.DS.'joomla'.DS.'kiss'.DS.'kiss-general.css');
	  $this->browser = "ff";
	  break;

	  case 'ie6':
	  case 'ie7':
	  case 'ie8':
	  case 'ie9':	  
	  case 'chrome':
		  $document->addStyleSheet(DS.'libraries'.DS.'joomla'.DS.'kiss'.DS.'kiss-general-ie7.css');
	  	  $this->browser = "ie";
	  break;
	  }
	  $document->addStyleSheet(DS.'libraries'.DS.'joomla'.DS.'kiss'.DS.'kiss-panels.css');
	}

	// Draws a link button
    	public function link_button($data = array())
    	{
	    $onclick = (isset($data['onclick'])) ? ' onclick="'.$data['onclick'].'"' : '';
	    $output = '<a class="kiss-' . (isset($data['classes']) && isset($data['classes']['a']) ? $data['classes']['a'] : 'silver')
		    . '-button" href="' . $data['link'] . '"'
			. $onclick
			. '><span>'
		    . $data['content'] . '</span></a>'
			;
	    return $output;
    	}
		

	// Draws a form button
    	public function form_button($data = array())
    	{
		$onclick = (isset($data['onclick'])) ? ' onclick="'.$data['onclick'].'"' : '';		
		$style = (isset($data['style'])) ? ' style="'.$data['style'].'"' : ' style="cursor: pointer;"';
		$type = (isset($data['type'])) ? ' type="'.$data['type'].'"' : '';
		$name = (isset($data['name'])) ? ' name="'.$data['name'].'"' : '';
		$id = (isset($data['id'])) ? ' id="'.$data['id'].'"' : '';
		$output = '<button class="kiss-' . (isset($data['classes']) && isset($data['classes']['a']) && strlen($data['classes']['a']) > 0 ? $data['classes']['a'] : 'silver')
		    . '-button" '
			. $name
			. $id
			. $type
			. $style
			. $onclick
			. '><span>'
		    . $data['content'] . '</span></button>'
			;			
		return $output;	
    	}
    } // Class End
	
    /*--------------------------------------------------------------------------------------------
      KISS Tabs Class. Creates and manages tabs.
     --------------------------------------------------------------------------------------------*/
class kissTabs {

	      /*------------------------------------------------------------
	        Section for Joomla 3.x, Bootstrap activated
  	        -----------------------------------------------------------*/
	      
	      /*------------------------------------------------------------
	        Initialize a tab set
  	        -----------------------------------------------------------*/
	      function startTabs($tabs) {

			$tb 	= JArrayHelper::getValue($tabs, 'tabs', 1);    
			$act	= JArrayHelper::getValue($tabs, 'active', 0);
			$tbg	= JArrayHelper::getValue($tabs, 'tabgroup', 'Tab-Group-ID');
			$tbo	= JArrayHelper::getValue($tabs, 'tabsOptions', array());
/*			
			$o = '<div class="tabbable">'."\n";
			$o .= '<ul class="nav nav-tabs" role="tablist">';
			$tb = JArrayHelper::getValue($tabs, 'tabs', 1);    
			for( $i=0; $i < $tb; $i++ ) {
				$tlarr = JArrayHelper::getValue($tabs, 'tabs_label', 'Tab '.$i+1);
				$tbid	= JArrayHelper::getValue($tabs, 'tabid', 'tab'.$i+1);
				if ($i == $act) {
					$o .= '<li class="active"><a href="#'. $tbid[$i] . '" role="tab" data-toggle="tab">' . $tlarr[$i] . '</a></li>'."\n";
				} else {
					$o .= '<li><a href="#'. $tbid[$i] . '" role="tab" data-toggle="tab">' . $tlarr[$i] . '</a></li>'."\n";
				}
			}
			$o .= '</ul>'."\n";
			$o .= '</div>'."\n";
*/			
			$o = JHtml::_('bootstrap.startTabSet', $tbg, $tbo);
			return $o;
	      }
	      /*------------------------------------------------------------
	        Creates the tab panels
	        -----------------------------------------------------------*/
	      function tabPanels($tabs) {
			$tb 	= JArrayHelper::getValue($tabs, 'tabs', 1);    
			$act	= JArrayHelper::getValue($tabs, 'active', 0);
			$fad	= JArrayHelper::getValue($tabs, 'fade', 1);

			$o = '<div class="tab-content">'."\n";
			for( $i=0; $i < $tb; $i++ ) {
			     $tbid	= JArrayHelper::getValue($tabs, 'tabid', 'tab'.$i+1);
			     $tbcn	= JArrayHelper::getValue($tabs, 'tabcontent', 'Text');
			     $active 	= ($i == $act) ? 'active' : '';
			     $fadein 	= ($i == $act && $fad) ? ' fade in' : '';
			     $fade 	= ($i != $act && $fad) ? ' fade' : '';
			     $o 	.= '<div class="tab-pane'.$fadein.$fade.'" id="'. $tbid[$i] .'">'."\n";
			     $o 	.= $tbcn[$i];
			     $o		.= '</div>'."\n";;
			  }
			$o .= '</div>'."\n";;
			return $o;
	      }
	      /*------------------------------------------------------------
	        Creates a tab panel
	        -----------------------------------------------------------*/
	      function startTab($tabgroup, $tabid, $title) {
		return JHtml::_('bootstrap.addTab', $tabgroup, $tabid, $title);
		}
	      function endTab() {
		return JHtml::_('bootstrap.endPanel');
		}
	      function endTabs() {
		return JHtml::_('bootstrap.endPane');
		}
	      /*------------------------------------------------------------
	        Section for Joomla 2.5, for compatibility reasons
		- deprecated -
  	        -----------------------------------------------------------*/

	      /*------------------------------------------------------------
	        Starts a tab pane
	        -----------------------------------------------------------*/
	      function startPane($id = 0, $options = Null){
		      return JHtml::_('tabs.start', $id, $options);
	      }

	      /*------------------------------------------------------------
	        Ends a tab pane
	        -----------------------------------------------------------*/
	      function endPane() {
		      return JHtml::_('tabs.end');
	      }

	      /*------------------------------------------------------------
	        Creates a tab with title text and starts a tab page
	        -----------------------------------------------------------*/
	      function startPanel( $tabText = '', $panelId = '0' ) {
		      return JHtml::_('tabs.panel', $tabText, $panelId);
	      }

	      /*------------------------------------------------------------
	        Ends a tab page - deprecated since 2.5
	        -----------------------------------------------------------*/
	      function endPanel() {
		      return '';
	      }

	} // Class End


class kissSliders {

	      /*------------------------------------------------------------
	        Section for Joomla 3.x, Bootstrap activated
  	        -----------------------------------------------------------*/

	      function startAccordion ($id, $options) {
		echo JHtml::_('bootstrap.startAccordion', $id, $options);
	      }
	      function startSlide($groupid, $title, $slideid) {
		echo JHtml::_('bootstrap.addSlide', $groupid, $title, $slideid);
	      }
	      function endSlide() {
	        echo JHtml::_('bootstrap.endSlide'); 
	      }
	      function endAccordion() {
	        echo JHtml::_('bootstrap.endAccordion'); 
	      }
	      /*------------------------------------------------------------
	        Section for Joomla 2.5, deprecated. For compatibility reasons
  	        -----------------------------------------------------------*/

	      /*------------------------------------------------------------
	        Starts a sliders pane
	        -----------------------------------------------------------*/
	      function startPane($id = 0, $options = Null){
		      return JHtml::_('sliders.start', $id, $options);
	      }

	      /*------------------------------------------------------------
	        Ends a Sliders pane
	        -----------------------------------------------------------*/
	      function endPane() {
		      return JHtml::_('sliders.end');
	      }

	      /*------------------------------------------------------------
	        Creates a slider with title text and starts a slider page
	        -----------------------------------------------------------*/
	      function startPanel( $sliderText = '', $panelId = '0', $sliderId = '0' ) {
		      return JHtml::_('sliders.panel', $sliderText, $panelId);
	      }

	      /*------------------------------------------------------------
	        Ends a slider page - deprecated since 2.5
	        -----------------------------------------------------------*/
	      function endPanel() {
		    return '';
	      }

	} // Class End	

} // Definition end