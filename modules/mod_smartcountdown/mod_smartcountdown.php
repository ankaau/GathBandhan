<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 - 3.0
 * @version 2.3 mod_smartcountdown.php
 * @author Alex Polonski
 * @copyright (C) 2012-2014 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;

$params->set('module_id', $module->id);

$params->set('cache', 0);

require_once dirname(__FILE__).'/helpers/helper.php';
$events_queue = modSmartCountdownHelper::getCountdown($params);

if($events_queue)
{
	// process the fisrt entry in the events queue (for php output)
	$countdown = $events_queue[0];
	
	JHtml::_('behavior.framework', true);
	
	$params->set('countdown_mode', $countdown['diff_js'] > 0 ? 'down' : 'up');
	
	$document = JFactory::getDocument();
	$document->addScript(JURI::root(true).'/modules/mod_smartcountdown/smartcdpro.js');
	
	$lang = JFactory::getLanguage('site');
	$tag = strtolower($lang->getTag());
	
	// Check if language-specific plural js file exists
	// (it must contain a function to set sigular/plural label forms)
	$file_name = 'plural_'.$tag.'.js';
	if(file_exists(dirname(__FILE__).'/plural_js/'.$file_name))
	{
		// use language-specific file
		$document->addScript(JURI::root(true).'/modules/mod_smartcountdown/plural_js/'.$file_name);
	}
	else 
	{
		// use default file (suitable for most languages)
		$document->addScript(JURI::root(true).'/modules/mod_smartcountdown/plural_js/plural.js');
	}
	$document->addStyleSheet(JURI::root(true).'/modules/mod_smartcountdown/styles.css');

	// get digits config
	$config = $params->get('digits_config', '');
	$fx_dir = JPATH_ROOT.'/modules/mod_smartcountdown/fx';
	$cfgFile = $fx_dir.'/'.($config ? $config : 'No_FX_animation.xml');
	if(empty($config) && $params->get('digits_xml', '')) {
		// Custom profile not empty
		$xml = $params->get('digits_xml', '');
	}
	else {
		// Preset profile
		if(JFile::exists($cfgFile)) {
			$xml = JFile::read($cfgFile);
		}
		else {
			$xml = false;
		}
	}
	
	$scaleBy = $params->get('scale_by', 1);
	$digitsConfig = modSmartCountdownHelper::getDigitsConfig($xml, $scaleBy);
	
	// get layout config
	$layoutConfig = modSmartCountdownHelper::getLayoutConfig($params);
	
	$options = new stdClass();
	$options->hide_zero_fields = $params->get('hide_zero_fields', 1);
	$options->display_seconds = $params->get('display_seconds', 1);
	$options->compact_view = $params->get('compact_view', 0);
	$options->show_countdown = $params->get('show_countdown', -1);
	$options->show_countup = $params->get('show_countup', -1);
	$options->images_folder = $digitsConfig['images_folder'];
	$options->blinking_separator = $params->get('separator_blink', 0);
	$options->left_pad_days = $params->get('left_pad_days', 0);
	
	// pass redirection type options, so that js can decide when to do the
	// redirection: "on deadline reached" or "on click"
	$options->event_goto = $params->get('event_goto', 0);
	$options->counter_clickable = $params->get('counter_clickable', 0);
	$options->confirm_redirect = $params->get('confirm_redirect', 1);
	$options->session_time_count = $params->get('session_time_count', 0);
	
	$options->min_days_width = $params->get('min_days_width', 0) == '' ? 0 : $params->get('min_days_width', 0);
	
	// Set layout correction method for js: 
	// 'table_layout' if the numbers divs min-width should be set to the higest value (table-cell style)
	// 'labels_vert_pos' if the labels should be vertically aligned (if they are on the same line as digits)
	$options->table_layout = $layoutConfig['counter_layout_class'] == 'column' && $layoutConfig['units_layout_class'] == 'horz';
	$options->labels_vert_pos = $layoutConfig['units_layout_class'] == 'horz' ? $params->get('labels_vert_pos', 'middle') : '';
	
	// Add language strings to options (do not depend on Joomla.Jtext._())
	$js_strings = array(
			'MOD_SMARTCDPRO_N_DAYS' => JText::_('MOD_SMARTCDPRO_N_DAYS'),
			'MOD_SMARTCDPRO_N_DAYS_1' => JText::_('MOD_SMARTCDPRO_N_DAYS_1'),
			'MOD_SMARTCDPRO_N_DAYS_2' => JText::_('MOD_SMARTCDPRO_N_DAYS_2'),
			'MOD_SMARTCDPRO_N_HOURS' => JText::_('MOD_SMARTCDPRO_N_HOURS'),
			'MOD_SMARTCDPRO_N_HOURS_1' => JText::_('MOD_SMARTCDPRO_N_HOURS_1'),
			'MOD_SMARTCDPRO_N_HOURS_2' => JText::_('MOD_SMARTCDPRO_N_HOURS_2'),
			'MOD_SMARTCDPRO_N_MINUTES' => JText::_('MOD_SMARTCDPRO_N_MINUTES'),
			'MOD_SMARTCDPRO_N_MINUTES_1' => JText::_('MOD_SMARTCDPRO_N_MINUTES_1'),
			'MOD_SMARTCDPRO_N_MINUTES_2' => JText::_('MOD_SMARTCDPRO_N_MINUTES_2'),
			'MOD_SMARTCDPRO_N_SECONDS' => JText::_('MOD_SMARTCDPRO_N_SECONDS'),
			'MOD_SMARTCDPRO_N_SECONDS_1' => JText::_('MOD_SMARTCDPRO_N_SECONDS_1'),
			'MOD_SMARTCDPRO_N_SECONDS_2' => JText::_('MOD_SMARTCDPRO_N_SECONDS_2'),
			'MOD_SMARTCDPRO_REDIRECT_CONFIRM_HINT' => JText::_('MOD_SMARTCDPRO_REDIRECT_CONFIRM_HINT')
	);
	
	$options->strings = $js_strings;
	
	// encode options for js
	$options = json_encode($options);
	
	// encode events queue for js
	$events_queue = json_encode($events_queue);
	
	/* Moved to module template, so that this script is loaded in module position
	 * and will be called if caching software is installed and active, but the
	 * module position is excluded from caching
	 * MAY NOT WORK IN OLD BROWSERS...
	$js_library = $params->get('js_library', '');
	if(empty($js_library)) {
		$script = '
			if(typeof(jQuery) != "undefined") {
				jQuery(document).ready(function() {
					scdpCounters.add('
						.$module->id.', '
						.$events_queue.', '
						.$options.');
				});
			} else {
				window.addEvent("domready", function() {
					scdpCounters.add('
						.$module->id.', '
						.$events_queue.', '
						.$options.');
				});
			}
		';
	} elseif($js_library == 1) { // mootools
		$script = '
				window.addEvent("domready", function() {
					scdpCounters.add('
						.$module->id.', '
						.$events_queue.', '
						.$options.');
				});
			
		';
	} elseif($js_library == 2) {
		$script = '
				jQuery(document).ready(function() {
					scdpCounters.add('
						.$module->id.', '
						.$events_queue.', '
						.$options.');
				});
		';
	}
	
	$document->addScriptDeclaration($script);
	*/
	
	// Add required data to $params, so that the trigger script can be called
	// it module template
	$params->set('events_queue_json', $events_queue);
	$params->set('options_json', $options);
	
	
	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	require JModuleHelper::getLayoutPath('mod_smartcountdown', $params->get('layout', 'default'));
}

