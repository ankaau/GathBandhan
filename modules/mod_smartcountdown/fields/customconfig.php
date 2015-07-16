<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 -3.0
 * @version 2.1: customconfig.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('textarea');

class JFormFieldCustomconfig extends JFormFieldTextarea
{
	public $type = 'Customconfig';
	
	public function getInput()
	{
		$html = array();
		$html[] = parent::getInput();
		
		// Auto-populate config text if a preset configuration is selected
		// Also change preset selection to "Custom" if the text in this field has been modified
		// so that it will not be overwrited on save
		$script = '
			window.addEvent("domready", function() {
				var thisControl = $("'.$this->getId('digits_xml', 'digits_xml').'");
				var textField = $("scdp_config_text");
				if(textField) {
					var text = textField.get("value").replace(/\t/g, "    ");
					thisControl.set("value", text);
				}
				thisControl.addEvent("change", function(e) {
					var presetControl = $("'.$this->getId('digits_config', 'digits_config').'");
					presetControl.set("value", "");
				});
			});
		';
		
		$document = JFactory::getDocument();
		$document->addScriptDeclaration($script);		
		
		$document->addStyleSheet(JURI::root(true).'/modules/mod_smartcountdown/styles.css');
		return implode("\n", $html);
		
	}
}