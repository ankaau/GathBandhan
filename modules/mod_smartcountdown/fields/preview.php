<?php
/**
 * @package Module Smart Countdown Pro for Joomla! 2.5 - 3.0
 * @version 2.1: preview.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

class JFormFieldPreview extends JFormField
{
	public $type = 'preview';
	
	public function getInput()
	{
		require_once JPATH_ROOT.'/modules/mod_smartcountdown/helpers/helper.php';
		
		$config_field = $this->element['config_field'] ? (string)$this->element['config_field'] : 'digits_config';
		$xml_field = $this->element['xml_field'] ? (string)$this->element['xml_field'] : 'digits_xml';
		$preview_mode_field = $this->element['preview_mode_field'] ? (string)$this->element['preview_mode_field'] : 'preview_mode';
		$digits_style_override_field = $this->element['digits_style'] ? (string)$this->element['digits_style'] : 'digits_style';
		
		$fx_dir = JPATH_ROOT.'/modules/mod_smartcountdown/fx';
		$config = $this->form->getValue($config_field, 'params', '');
		if(empty($config)) {
			$xml = $this->form->getValue($xml_field, 'params', '');
		}
		else {
			if(JFile::exists($fx_dir.'/'.$config)) {
				$xml = JFile::read($fx_dir.'/'.$config);
			}
			else {
				return '<div class="error">'.JText::_('MOD_SMARTCDPRO_ERROR_CONFIG_FILE_NOT_EXISTS').'</div>';
			}
		}
		
		$mode = $this->form->getValue($preview_mode_field, 'params', 'down');
		
		$style = $this->form->getValue($digits_style_override_field, 'params', '');
		$style = $style ? ' style="'.$style.'"' : '';
		
		$scaleBy = $this->form->getValue('scale_by', 'params', 1);
		$config = modSmartCountdownHelper::getDigitsConfig($xml, $scaleBy);
		
		if(!$config) {
			return '<div class="error">'.JText::_('MOD_SMARTCDPRO_ERROR_INVALID_XML_CONFIG').'</div>';
		}
				
		JHtml::addIncludePath(JPATH_ROOT.'/modules/mod_smartcountdown/helpers/html');
		
		$html[] = '<div class="clear"><em>'.$config['name'].'</em></div>';
		$html[] = '<div class="clear"><em>'.$config['description'].'</em></div>';
		
		$html[] = '<div id="scdp-digits-preview"'.$style.'>';
		
		$preview_values = array();
		$preview_index = 0;
		
		$imagesFolder = $config['images_folder'];
		
		foreach($config['digits'] as $scope => $data) {
			$html[] = '<fieldset>';
			$html[] = '<legend>Scope: '.$scope.'</legend>';
				$html[] = JHtml::_('smartcdpro.digit', 0, $preview_index, $data,
					array('mode' => $mode, 'images_folder' => $imagesFolder, 'ids_suffix' => '_0'));

			$html[] = '</fieldset>';
			
			$preview_values[] = 0;
			$preview_index++;
		}
		
		$digitsCount = count($preview_values);
		$values = json_encode($preview_values);
		
		$html[] = '</div>';
		
		$html[] = '<div class="clr"></div>';

		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/modules/mod_smartcountdown/smartcdpro.js');
		
		
		$script = '
			window.addEvent("domready", function() {
				
				var mode = $("jform_params_preview_mode").getChildren("input:checked").pop().get("value");
				var scdp_preview = new ScdpDigits('.$values.',{images_folder:"'.$imagesFolder.'", mode : mode});
				
				var values = [];
				var preview_delta = (mode == "down" ? -1 : 1);
				var scdp_preview_value = 0;
				function scdp_go_preview() {
					scdp_preview_value = scdp_preview_value + preview_delta;
					
					if(scdp_preview_value > 9) {
						scdp_preview_value = 0;
					}
					if(scdp_preview_value < 0) {
						scdp_preview_value = 9;
					}
					
					for(i = 0; i < '.$digitsCount.'; i++) {
						values[i] = scdp_preview_value;
					}
					
					scdp_preview.start(values, false);
				}
				window.setInterval(scdp_go_preview, 5000);
			});
		';
		
		$document->addScriptDeclaration($script);		
		
		$document->addStyleSheet(JURI::root(true).'/modules/mod_smartcountdown/styles.css');
		return implode("\n", $html);
		
	}
}