<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 -3.0
 * @version 2.3.2: helper.php
 * @author Alex Polonski
 * @copyright (C) 2012-2013 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;
jimport('joomla.event.dispatcher');

class modSmartCountdownHelper
{
	static function getCountdown($params)
	{
		// Explicitly disable caching for this module
		$params->set('cache', 0);
		
		// look for events import plugins
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('system');
		$result = $dispatcher->trigger('onCountdownGetEventsQueue', array('mod_smartcountdown', $params));
		if(in_array(false, $result, true)) {
			return false;
		}
		
		// get combined events queue from all plugins
		$groups = self::unionQueues($result);
		
		if($groups === false || $params->get('session_time_count', 0) || $params->get('submit_form', ''))
		{
			// all elements of the result array === true: means that no plugin
			// is enabled. This is not an error, just get internal countdown
			// Also if counter mode is set to "relative to session start" or
			// "submit form on countdown zero" is not empty, we ignore all plugin data
			return self::getInternal($params);
		}
		
		// we have event queue here, let's proceed
		
		$show_countdown = $params->get('show_countdown', -1); // auto
		
		$now_ts = JDate::getInstance()->getTimestamp();
		
		/**
		 * Queue format: 2-dimensional array
		 * 1-st dimension - events indexed by start time
		 * 2-nd dimension - events indexed by end time
		 */
		
		/*
		 * Re-process all events provided by events import plugins.
		 * *** current versions of plugins can be significantly simplified ***
		 * For new plugins - no need to calculate diff_js and up_limit in plugin
		 * code, as these values will be overwritten here. At the moment it is just
		 * a performance issue
		 */
		
		$past_slice = null;
		
		$start_times = array_keys($groups);
		foreach($start_times as $i => $time)
		{
			// we have to filter out all past groups except the most recent one
			if($time < $now_ts)
			{
				if(isset($past_slice))
				{
					// we can safely use $i - 1 index, as $past_slice will never be
					// set on the first iteration s_tyles
					
					// unset previous group
					unset($groups[$start_times[$i - 1]]);
				}
				// set flag: at least one past event found
				$past_slice = $time;
			}
			
			$next_start = @$start_times[$i+1];
			if(isset($next_start))
			{
				$events_hop = $next_start - $time;
			}
			else
			{
				$events_hop = PHP_INT_MAX - $time;
			}
		
			foreach($groups[$time] as $end => &$event)
			{
				$duration = $end - $time;
				
				if($show_countdown == 0) 
				{
					// do not show countdown - conrinue count up until the next event start
					$event['up_limit'] = $events_hop;
				}
				else if($show_countdown == -2)
				{
					// do not show count up - start countdown right after previous event start
					$event['up_limit'] = 0;
				}
				else if($show_countdown == -1)
				{
					// auto - use event duration
					$event['up_limit'] = $events_hop == 0 || $events_hop > $duration ? $duration : $events_hop;
				}
				else if($show_countdown > 0)
				{
					// positive value means time to show countdown before the next event start
					// if greater than events hop, use event hop instead
					$event['up_limit'] = $events_hop > $show_countdown ? $events_hop - $show_countdown : $events_hop;
				}
				else
				{
					// negative value means time to show count up after the event start
					// if greater than events hop, use event hop instead
					$show_countup = $show_countdown * -1;
					$event['up_limit'] = $show_countup > $events_hop ? $events_hop : $show_countup;
				}
				
				// up_limit can never be greater than duration
				$event['up_limit'] = min($event['up_limit'], $duration);
			}
		}
		
		// Concatenate titles for duplicate-time events. We add support for different titles
		// in countdown and count up modes. Plugins can e.g. configure event links according
		// to their settings
		// We presume that if $tevent['title'] is set we have equal titles for both modes,
		// otherwise we look in $tevent['title_down'] and $tevent['title_up'] properties
		$titles_down = array();
		$titles_up = array();
		foreach($groups as $time => $tgroup)
		{
			foreach(array_reverse($tgroup, true) as $tevent)
			{
				if(isset($tevent['title']))
				{
					// the same title for both countdown and count up modes
					if(isset($titles_down[$time]))
					{
						$titles_down[$time][''] = $tevent['title'] . ', ' . $titles_down[$time][''];
						$titles_up[$time][''] = $tevent['title'] . ', ' . $titles_up[$time][''];
					}
					else
					{
						$titles_down[$time][''] = $tevent['title'];
						$titles_up[$time][''] = $tevent['title'];
					}
						
					$titles_down[$time][$tevent['up_limit']] = $titles_down[$time][''];
					$titles_up[$time][$tevent['up_limit']] = $titles_up[$time][''];
				}
				else 
				{
					// different titles for countdown and count up modes
					
					// titles down
					if(isset($titles_down[$time]))
					{
						$titles_down[$time][''] = $tevent['title_down'] . ', ' . $titles_down[$time][''];
					}
					else
					{
						$titles_down[$time][''] = $tevent['title_down'];
					}
					
					$titles_down[$time][$tevent['up_limit']] = $titles_down[$time][''];
					
					// titles up
					if(isset($titles_up[$time]))
					{
						$titles_up[$time][''] = $tevent['title_up'] . ', ' . $titles_up[$time][''];
					}
					else
					{
						$titles_up[$time][''] = $tevent['title_up'];
					}
						
					$titles_up[$time][$tevent['up_limit']] = $titles_up[$time][''];
				}
			}
		}
		
		// flatten the groups array and get events timeline
		
		// start the output array
		$events = array();
		
		foreach($groups as $time => $group) {
			foreach($group as $e) {
				$events[] = $e;
			}
		}
		
		// filter out expired events
		
		$recent_past_event = null;
		
		foreach($events as $i => $event)
		{
			if($i == 0 && $show_countdown >= 0 && $events[$i]['start'] - $now_ts > $show_countdown) {
				// for the first event in the queue do not render counter at all
				// if show_countdown is set for a value smaller than event start - now
				return array();
			}
			
			if($show_countdown < -1 && $now_ts - $event['start'] > $show_countdown * -1)
			{
				// even if the event is current (not finished) but 'show countup' limit is
				// exeeded, remove event
				unset($events[$i]);
			}
				
			if($show_countdown > 0 && isset($events[$i + 1]) && $events[$i + 1]['start'] - $now_ts < $show_countdown)
			{
				// if next event 'show countdown' already wins, remove event - it will not be
				// displayed anyway (1-second flicker workaround)
				unset($events[$i]);
			}
		}
		
		$events = array_values($events);
		
		$lang = JFactory::getLanguage();
		$tag = strtolower($lang->getTag());
			
		// Final pass through events: calculate diff_js, diff_php for first event and
		// setup event texts
		foreach($events as $i => &$event)
		{
			if($i == 0)
			{
				$event['diff_js'] = $events[$i]['start'] - $now_ts;
				
				// only first event in php format
				$seconds = $event['diff_js'] > 0 ? $event['diff_js'] : $event['diff_js'] * -1;
				$days_diff = floor($seconds / 86400);
				$diff_time = gmdate('H:i:s', $seconds);
				list($h_diff, $i_diff, $s_diff) = explode(':', $diff_time);
				$event['diff_php'] = array('days' => $days_diff, 'hours' => $h_diff, 'minutes' => $i_diff, 'seconds' => $s_diff);
			}
			else
			{
				$prev = $events[$i - 1];
				$event['diff_js'] = $event['start'] - $prev['start'] - $prev['up_limit'];
				
				$event['diff_php'] = array();
			}
			
			// construct event texts
			$text_down = $params->get('text_down_'.$tag,
					$params->get('text_down_def', JText::_('MOD_SMARTCDPRO_TEXT_DOWN_DEFAULT')));
			$text_up = $params->get('text_up_'.$tag,
					$params->get('text_up_def', JText::_('MOD_SMARTCDPRO_TEXT_UP_DEFAULT')));
			
			$replace = array("\n", "\r\n", "\r");
			$text_down = str_replace($replace, '<br />', $text_down);
			$text_up = str_replace($replace, '<br />', $text_up);
				
			// plugins can define the order of the event text concatenation
			if(!empty($event['title_first'])) {
				// event title goes first
				$text_down = $titles_down[$event['start']][$event['up_limit']].' '.$text_down;
				$text_up = $titles_up[$event['start']][$event['up_limit']].' '.$text_up;
			} else {
				// generic text goes first, just append the title
				$text_down .= ' '.$titles_down[$event['start']][$event['up_limit']];
				$text_up .= ' '.$titles_up[$event['start']][$event['up_limit']];
			}
				
			$event['text_down'] = $text_down;
			$event['text_up'] = $text_up;
			$event['text'] = $events[$i]['diff_js'] > 0 ? $events[$i]['text_down'] : $events[$i]['text_up'];
		}
		
		return $events;
	}
	
	private static function unionQueues($queues)
	{
		$events_queue = array();
		$plugins_enabled = false;
		
		foreach($queues as $queue)
		{
			if($queue === true)
			{
				continue;
			}
			$plugins_enabled = true;
			
			foreach($queue as $time => $group)
			{
				if(!isset($events_queue[$time]))
				{
					// empty start time slot - just add the whole group
					$events_queue[$time] = $group;
				}
				else 
				{
					// start time slot with existing group(s)
					// merge events
					foreach($group as $end_time => $event)
					{
						while(isset($events_queue[$time][$end_time]))
						{
							// make sure that end time slot is free, adding '0'
							// to the left of the array key will not affect
							// numeric ordering, but allows to avoid keys
							// duplicates
							$end_time = '0' . $end_time;
						}
						$events_queue[$time][$end_time] = $event;
					}
				}
			}
		}
		
		// apply ordering to all events in queue
		foreach($events_queue as &$group)
		{
			ksort($group, SORT_NUMERIC);
		}
		ksort($events_queue, SORT_NUMERIC);
		
		if($plugins_enabled)
		{
			return $events_queue;
		}
		else
		{
			return false;
		}
	}
	
	private static function getInternal($params)
	{
		$deadline = $params->get('deadline', '');
		
		$app = JFactory::getApplication();
		$session_time_count = $params->get('session_time_count', 0);
		$ids_suffix = '_'.$params->get('module_id', '');
		
		if($session_time_count)
		{
			$deadline = $app->getUserState('mod_smartcountdown' . $ids_suffix . '.session_deadline', null);
			
			if(empty($deadline))
			{
				$date = JFactory::getDate('now');
				if($session_time_count == -1)
				{
					$deadline_ts = $date->getTimestamp();
				}
				else
				{
					$deadline_ts = $date->getTimestamp() + $session_time_count;
				}
				$deadline = JFactory::getDate($deadline_ts)->toSql();
				$app->setUserState('mod_smartcountdown' . $ids_suffix . '.session_deadline', $deadline);
			}
		}
		else
		{
			$app->setUserState('mod_smartcountdown' . $ids_suffix . '.session_deadline', null);
		}

		if($deadline)
		{
			$deadline = JFactory::getDate($deadline);
			$date = JFactory::getDate('now');
			$diff_js = $deadline->format('U', false) - $date->format('U', false);
			
			// Check if an option is set for current mode (countdown/countup)
			
			$show_countdown = $params->get('show_countdown', -1);
			if($show_countdown >= 0 && $diff_js > $show_countdown)
			{
				// too early to show countdown
				return false;
			} 
			if(($show_countdown <= -2 && $diff_js <= $show_countdown) || ($show_countdown == -2 && $diff_js <= 0) /* workaround from $diff_js = -1 */)
			{
				// too late to show count up
				return false;
			}
			
			// for auto-countdowns ($show_countdown = -1) always show
			
			if($show_countdown >= -1) 
			{
				$up_limit = PHP_INT_MAX;
			}
			elseif($show_countdown == -2)
			{
				$up_limit = 0;
			}
			else
			{
				$up_limit = $show_countdown * -1;
			}
			
			// Set event text (countdown or countup)
			$lang = JFactory::getLanguage();
			$tag = strtolower($lang->getTag());
			
			// First try to find language specific strings, then fall back to
			// uni-language string (if no localization plugin is installed) and
			// as last resource use default text from ini file
			$text_down = $params->get('text_down_'.$tag,
					$params->get('text_down_def', JText::_('MOD_SMARTCDPRO_TEXT_DOWN_DEFAULT')));
			$text_up = $params->get('text_up_'.$tag,
					$params->get('text_up_def', JText::_('MOD_SMARTCDPRO_TEXT_UP_DEFAULT')));

			// strip new lines from texts to make them valid js strings
			$replace = array("\n", "\r\n", "\r");
			$text_down = str_replace($replace, ' ', $text_down);
			$text_up = str_replace($replace, ' ', $text_up);
					
			$text = $diff_js > 0 ? $text_down : $text_up;
						
			// Calculate values for php output (for users without js and for first page load)
			$seconds = $diff_js > 0 ? $diff_js : $diff_js * -1;
			$days_diff = floor($seconds / 86400);
			$diff_time = gmdate('H:i:s', $seconds);
			list($h_diff, $i_diff, $s_diff) = explode(':', $diff_time);
			
			// Process on event goto option
			if($params->get('event_goto', 0) || $params->get('counter_clickable', 0))
			{
				$event_goto_url = $params->get('event_goto_url', '');
				$event_goto_menu = empty($event_goto_url) ? $params->get('event_goto_menu', 0) : 0;
				$event_submit_form = $params->get('submit_form', '');
			}
			else 
			{
				$event_goto_menu = 0;
				$event_goto_url = '';
				$event_goto_link = '';
				$event_submit_form = '';
			}
			if($event_goto_menu)
			{
				$menu = JFactory::getApplication()->getMenu();
				$item = $menu->getItem($event_goto_menu);
				
				// we have to check if we have a valid item here: missing item
				// can be caused by menu item unpublished state
				if(!empty($item))
				{
					$router = JSite::getRouter();
					if ($router->getMode() == JROUTER_MODE_SEF)
					{
						$event_goto_link = 'index.php?Itemid='.$event_goto_menu;
					}
					else
					{
						$event_goto_link = $item->link.'&Itemid='.$event_goto_menu;
					}
					$event_goto_link .= '&lang='.substr($item->language, 0, 2);
					if (strcasecmp(substr($event_goto_link, 0, 4), 'http') && (strpos($event_goto_link, 'index.php?') !== false))
					{
						$event_goto_link = JRoute::_($event_goto_link, true, $item->params->get('secure'));
					}
					else
					{
						$event_goto_link = JRoute::_($event_goto_link);
					}
					$event_goto_link = JURI::getInstance()->toString(array('scheme', 'host')).$event_goto_link;
				}
				else
				{
					$event_goto_link = '';
				}
			}
			if($event_goto_url)
			{
				// workaround for duplicated 'http://' prefix prepended to url
				// for relative urls (e.g. index.php)
				if(strripos($event_goto_url, 'http') !== 0)
				{
					$event_goto_url = substr($event_goto_url, stripos($event_goto_url, '://') + 3);
				}
				$event_goto_link = $event_goto_url;
				// ignore empty url
				if(substr($event_goto_link, -3) == '://')
				{
					$event_goto_link = '';
				}
			}
			if($event_submit_form)
			{
				// if submit form is not empty, make counter not clickable and do not allow redirect confirmation
				$event_goto_link = trim($event_submit_form);
				$params->set('counter_clickable', 0);
				$params->set('confirm_redirect', 0);
			}
			
			// build result
			$result = array();
			// construct events queue with one entry only
			$result[] = array(
					'diff_js' => $diff_js,
					'up_limit' => $up_limit,
					'text' => $text,
					'text_down' => $text_down,
					'text_up' => $text_up,
					'diff_php' => array('days' => $days_diff, 'hours' => $h_diff, 'minutes' => $i_diff, 'seconds' => $s_diff),
					'event_goto_link' => $event_goto_link
			);
			return $result;
		}
		return false;
	}
	
	static function getDigitsConfig($xml, $scale = 1.0)
	{
		// compatibility workaround: in some environments XML document is
		// saved with "<styles>" element opening tags replaced with
		// "<s-tyles>". We allow this replace (as it can be DB security
		// concerned: looks like an HTML element <style...), but we need
		// to recover original XML syntax
		$xml = str_replace('<s-tyles>', '<styles>', $xml);
		
		// now XML document should be valid
		libxml_use_internal_errors(true);
				
		$xml = simplexml_load_string($xml);
		
		foreach (libxml_get_errors() as $error)
		{
			JLog::add(JText::_('MOD_SMARTCOUNTDOWN').': ' . $error->message, JLog::WARNING);
		}
		if(empty($xml))
		{
			return false;
		}
		
		$digitsConfig = array();
		
		// global settings
		$digitsConfig['name'] = $xml['name'] ? (string)$xml['name'] : 'Custom';
		$digitsConfig['description'] = $xml['description'] ? (string)$xml['description'] : '';
		
		$digitsConfig['images_folder'] = JURI::root().($xml['images_folder'] ? (string)$xml['images_folder'] : '');
		
		// get all digit scopes configurations
		foreach($xml->digit as $digit) {
			
			// scope attribute may contain more than one value (comma-separated list)
			$scopes = explode(',', (string)$digit['scope']);
			
			foreach($scopes as $scope) {
				// init config for all scopes in list
				$digitsConfig['digits'][$scope] = array();
			}
			
			// construct digit style
			$styles = '';
			foreach($digit->styles->style as $value) {
				$attrs = array();
				foreach($value->attributes() as $k => $v) {
					$attrs[$k] = (string)$v;
				}
				
				// Scale the value if it has 'scalable' attribute set
				$result = !empty($attrs['scalable']) ? $scale * $attrs['value'] : $attrs['value'];
				$styles .= $attrs['name'].':'.$result.(!empty($attrs['unit']) ? $attrs['unit'] : '').';';
			}
			
			// for digit style - if background set, prepend images_folder
			$styles = preg_replace('#url\((\S+)\)#', 'url('.$digitsConfig['images_folder'].'$1)', $styles);
			
			foreach($scopes as $scope) {
				// set styles for all scopes in list
				$digitsConfig['digits'][$scope]['style'] = $styles;
			}
			
			// get modes (down and up)
			foreach($digit->modes->mode as $groups) {
				
				$attrs = $groups->attributes();
				$mode = (string)$attrs['name'];
				
				foreach($groups as $group) {
					
					$grConfig = array();
					
					$grAttrs = $group->attributes();
					foreach($grAttrs as $k => $v) {
						$grConfig[$k] = (string)$v;
					}
					
					$grConfig['elements'] = array();
					
					// get all elements for the group
					foreach($group as $element) {
						// default values to use if attribute is missing
						$elConfig = array('filename_base' => '', 'filename_ext' => '', 'value_type' => '');
						
						$elAttrs = $element->attributes();
						foreach($elAttrs as $k => $v) {
							$elConfig[$k] = (string)$v;
						}
						
						$elConfig['styles'] = self::getElementStyles($element->styles, $digitsConfig['images_folder']);
						$elConfig['tweens'] = self::getElementTweens($element->tweens);
						
						$grConfig['elements'][] = $elConfig;
					}
					
					foreach($scopes as $scope) {
						// set fx configuration for all scopes in list
						$digitsConfig['digits'][$scope][$mode][] = $grConfig;
					}
				}
			}
		}
		
		return $digitsConfig;
	}
	
	private static function getElementStyles($styles, $images_folder)
	{
		$result = '';
		
		if(empty($styles)) {
			return $result;
		}
		
		$tpl = '%s:%s;';
		$styles = $styles->children();
		
		for($i = 0; $count = count($styles), $i < $count; $i++) {
			$result .= sprintf($tpl, $styles[$i]->getName(), (string)$styles[$i]);
		}
		
		// if styles contain background - prepend images_folder
		$result = preg_replace('#url\((\S+)\)#', 'url('.$images_folder.'$1)', $result);

		return $result;
	}
	
	private static function getElementTweens($tweens)
	{
		$result = new stdClass();
		
		if(empty($tweens)) {
			return $result;
		}
		
		$tweens = $tweens->children();
		
		for($i = 0; $count = count($tweens), $i < $count; $i++) {
			$name = $tweens[$i]->getName();
			$result->$name = explode(',', (string)$tweens[$i]);
		}
		
		return $result;
	}
	
	public static function getLayoutConfig($params)
	{
		$config = array();
		
		$config['hide_zero_fields'] = $params->get('hide_zero_fields', 1);
		$config['display_seconds'] = $params->get('display_seconds', 1);

		$module_width = $params->get('module_width', '');
		$module_padding = $params->get('module_padding', 0);
		$module_background = $params->get('module_background', '');
		$module_style = ($module_width ? 'width:'.$module_width.'px;' : '');
		$module_style .= ($module_padding ? 'padding:'.$module_padding.'px;' : '');
		$module_style .= ($module_background ? 'background:'.str_replace('"', "'", $module_background).';' : '');
		if($params->get('global_center', 1)) {
			// add global horizontal centering feature
			$module_style .= ($params->get('global_center', 1) ? 'text-align:center;' : '');
			$config['wrapper_class'] = ' class="scdp-wrapper-center"';
		} else {
			$config['wrapper_class'] = '';
		}
		$config['module_style'] = $module_style;
		
		$event_text_size = $params->get('event_text_size', 20);
		$event_text_style = $params->get('event_text_style', '');
		$event_text_style = 'font-size:'.$event_text_size.'px;'.$event_text_style;
		$config['event_text_style'] = $event_text_style;
		
		$numbers_style = $params->get('digits_style', '');
		$config['numbers_style'] = $numbers_style;
		
		
		$labels_size = $params->get('labels_size', 20);
		$labels_style = $params->get('labels_style', '');
		$labels_style = 'font-size:'.$labels_size.'px;'.$labels_style;
		$config['labels_style'] = $labels_style;
		
		if($params->get('overload_labels', 0)) {
			$tpl = $params->get('overload_labels_tpl', '');
		}
		else {
			$tpl = '%|%|%|%';
		}
		$tpl = explode('|', $tpl);
		$tpl = array_slice(array_pad($tpl, 4, ''), 0, 4);
		
		$config['label_types'] = array_combine(array('days', 'hours', 'minutes', 'seconds'), $tpl);
		$config['separator_image'] = $params->get('separator_image', '');
		
		$textPosition = $params->get('text_position', 'top');
		$labelsPosition = $params->get('labels_position', 'right');
		
		$textBeforeCounter = $textPosition == 'left' || $textPosition == 'top';
		$labelsBeforeNumbers = $labelsPosition == 'left' || $labelsPosition == 'top';
		
		$config['text_layout_class'] = $textPosition == 'left' || $textPosition == 'right' ? 'horz' : 'vert';
		$config['counter_layout_class'] = $params->get('counter_layout', 'column');
		$config['units_layout_class'] = $labelsPosition == 'left' || $labelsPosition == 'right' ? 'horz' : 'vert';
		
		$config['text_before_counter'] = $textBeforeCounter;
		$config['labels_before_numbers'] = $labelsBeforeNumbers;
		
		$config['counter_style'] = $params->get('counter_style', '');
		if(strpos($config['counter_style'], 'margin') === false) {
			// use 'text_spacing' setting if no margins are set in 'counter_style'
			$text_spacing = $params->get('text_spacing', 6);
			$config['counter_style'] = $config['counter_style'].'margin-'.$textPosition.':'.$text_spacing.'px;';
		}
		if($params->get('global_center', 1)) {
			// add global horizontal centering feature
			$config['counter_style'] .= 'display:inline-block;';
		}
		
		$units_spacing = $params->get('units_spacing', 4);
		$units_style = $config['counter_layout_class'] == 'column' ?
			'padding:'.($units_spacing / 2).'px 0;' :
			'padding:0 '.($units_spacing / 2).'px;' ;
		$config['units_style'] = $units_style;
		
		$labels_spacing = $params->get('numbers_lables_spacing', 8);
		$labels_margin = 'margin-'.$labelsPosition.':'.$labels_spacing.'px;';
		$config['numbers_style'] = $labels_margin.$config['numbers_style'];
		
		$config['module_style'] = $config['module_style'] ? ' style="'.$config['module_style'].'"' : '';
		$config['event_text_style'] = $config['event_text_style'] ? ' style="'.$config['event_text_style'].'"' : '';
		$config['counter_style'] = $config['counter_style'] ? ' style="'.$config['counter_style'].'"' : '';
		
		// special for reserved space setting for day digits
		$min_days_width = $params->get('min_days_width', 0) == '' ? 0 : $params->get('min_days_width', 0);
		$config['days_number_style'] = ' style="'.$config['numbers_style'].'min-width:'.$min_days_width.'px;"';
		
		$config['numbers_style'] = $config['numbers_style'] ? ' style="'.$config['numbers_style'].'"' : '';
		$config['labels_style'] = $config['labels_style'] ? ' style="'.$config['labels_style'].'"' : '';
		
		return $config;
	}
	
	public static function getNumber($name, $values)
	{
		$assets = array(
			'days' => array(6, null), 
			'hours' => array(4, 2),
			'minutes' => array(2, 2),
			'seconds' => array(0, 2)
		);

		// digits floating left, so we start with the highest digit here...
		$digits = array_reverse(array_slice($values, $assets[$name][0], $assets[$name][1], true), true);
		$value = implode('', $digits);
		
		return array('digits' => $digits, 'value' => $value);
	}
}

