<?php
 
 /**
 * @package Spider Calendar lite
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
		$document = &JFactory::getDocument();
		$document->addScript(JUri::root().'modules/mod_spidercalendar/elements/jscolor/jscolor.js' );
		return parent::getInput();
	}

	public function setup(& $element, $value, $group = null)
	{

		$return= parent::setup($element, $value, $group);
		$this->element['class'] = $this->element['class'].' color';
		if($this->element['name']!='title_color' and $this->element['name']!='date_color')
		$this->element['onchange'] = 'document.getElementById(\'jform_params_calendar_style\').value=\'custom\'';
		return $return;
	}
}
?>