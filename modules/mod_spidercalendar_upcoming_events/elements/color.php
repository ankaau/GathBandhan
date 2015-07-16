<?php
 
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die( 'Restricted access' );

class JFormFieldColor extends JFormFieldText
{
	protected $type = 'Color';

	public function getInput()
	{
		$document = JFactory::getDocument();
		$document->addScript(JUri::root().'modules/mod_spidercalendar_upcoming_events/elements/jscolor/jscolor.js' );
		return parent::getInput();
	}

	public function setup(SimpleXMLElement $element, $value, $group = null)
	{

		$return= parent::setup($element, $value, $group);
		$this->element['class'] = $this->element['class'].' color';
		
		return $return;
	}

}
?>