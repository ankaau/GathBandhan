<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 - 3.0
 * @version 2.3.2: smartcdpro.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;

abstract class JHtmlSmartCDPro
{		
	public static function number($digits, $config, $ids_suffix, $mode)
	{
		$html = array();
		
		$html[] = '<div class="scdp-digits-wrapper">';
		
		foreach($digits as $n => $digit) {
			$configKey = $n > 6 ? 6 : $n; // ignore digits config for day digits except the lowest one
			$dConfig = isset($config['digits'][$configKey]) ? $config['digits'][$configKey] : $config['digits']['*'];
			
			if(empty($dConfig)) {
				return 'Error: Invalid Configuration';
			}
			
			$options = array(
				'ids_suffix' => $ids_suffix,
				'mode' => $mode,
				'images_folder' => $config['images_folder']
			);
				
			$html[] = self::digit($digit, $n, $dConfig, $options);
		}
		
		$html[] = '</div>';
		
		return implode("\n", $html);
	}
	
	public static function digit($value, $digit_n_sfx, $digitConfig, $options)
	{
		$html = array();
		$data = $digitConfig[$options['mode']];
		
		$wrapper_style = $digitConfig['style'] ? ' style="'.$digitConfig['style'].'"' : '';
		
		$html[] = '<div class="sc-fx-wrapper"'.$wrapper_style.' id="sc'.$options['ids_suffix'].'_wrapper_'.$digit_n_sfx.'">';
		
		// get all unique elements
		$elements = array();
		foreach($data as $group) {
			foreach($group['elements'] as $element) {
				if(self::isUnique($elements, $element)) {
					$elements[] = $element;
				}
			}
		}
		
		// display elements (it will be recreated in js script initialization
		// do it here to get some display if javascript is desactivated in the client's browser)
		foreach($elements as $element) {
			$style = $element['styles'];
				
			if($style) {
				$style = ' style="'.$style.'"';
			}
			
			switch($element['content_type']) {
				case 'img':
					$src = $options['images_folder'].$element['filename_base'].$value.$element['filename_ext'];
					$html[] = '<img class="scdp-fx-element" alt="'.$value.' "src="'.$src.'"'.$style.' />';
					break;
				case 'static-bg':
					if($element['tag'] == 'img') {
						$src = $options['images_folder'].$element['filename_base'].$element['filename_ext'];
						$html[] = '<img class="sc-static_element" src="'.$src.'"'.$style.' />';
					}
					else { // div
						$html[] = '<div class="sc-static-element"'.$style.'></div>';
					}
					break;
				case 'uni-img':
					$src = $options['images_folder'].$element['filename_base'].$element['filename_ext'];
					$html[] = '<img class="scdp-fx-element" alt="" src="'.$src.'"'.$style.' />';
					break;
				default: // txt
					$html[] = '<div class="scdp-fx-element"'.$style.'>'.$value.'</div>';
			}
		}
		
		// inject digit configuration
		$html[] = '<input type="hidden" value=\''.json_encode($digitConfig).'\' />';
		$html[] = '</div>';
		
		return implode("\n", $html);
	}
	
	public static function label($name, $value, $text = null, $isImage = false)
	{
		if($text === null) {
			$label_html = JText::plural('MOD_SMARTCDPRO_N_'.strtoupper($name), $value);
			$type = 'label';
		}
		else {
			$type = 'separator';
			if($isImage) {
				$label_html = '<img class="scdp-separator" src="'.$text.'" alt="separator" />';
			}
			else {
				$label_html = $text;
			}
		}
		
		$html = array();
		
		$html[] = '<div class="scdp-label-wrapper type-'.$type.'">';
			$html[] = $label_html;
		$html[] = '</div>';
		
		return implode("\n", $html);
	}
	
	private static function isUnique($elements, $element)
	{
		foreach($elements as $item) {
			if(
				$item['tag'] == $element['tag'] &&
				$item['content_type'] == $element['content_type'] &&
				$item['value_type'] == $element['value_type'] &&
				$item['filename_base'] == $element['filename_base'] &&
				$item['filename_ext'] == $element['filename_ext']
			) {
					return false;
			}
		}
		return true;
	}
}

