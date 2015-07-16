<?php
/**
 * @package Module Smart Countdown for Joomla! 1.7 - 2.5
 * @version 2.3: default.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
// no direct access
defined('_JEXEC') or die;

// disable browser cache. Workaround for Joomla 2.5 issue: pressing browser back button
// bypasses the server code and leads to incorrect counter values
JResponse::setHeader('Cache-Control', 'no-cache, max-age=0, must-revalidate, no-store', true);

// get ids suffix (used to avoid elements ids conflicts
// when more than one countdown on the same page
$ids_suffix = '_'.$params->get('module_id', '');

require_once(JPATH_ROOT.'/modules/mod_smartcountdown/helpers/html/smartcdpro.php');
require_once(JPATH_ROOT.'/modules/mod_smartcountdown/helpers/helper.php');

$values = str_split(strrev(
		str_pad($countdown['diff_php']['days'], $params->get('left_pad_days', 0), '0', STR_PAD_LEFT).
		$countdown['diff_php']['hours'].
		$countdown['diff_php']['minutes'].
		$countdown['diff_php']['seconds']
));

$mode = $params->get('countdown_mode', 'down');

$displayAll = !$layoutConfig['hide_zero_fields'];
$displaySeconds = $layoutConfig['display_seconds'];
$labelTypes = $layoutConfig['label_types'];

$assets = array(
	'days' => $displayAll || $countdown['diff_php']['days'] != 0,
	'hours' => $displayAll || ($countdown['diff_php']['days'] + $countdown['diff_php']['hours'] != 0),
	'minutes' => $displayAll || ($countdown['diff_php']['days'] + $countdown['diff_php']['hours'] + $countdown['diff_php']['minutes'] != 0),
	'seconds' => $displaySeconds || ($countdown['diff_php']['days'] + $countdown['diff_php']['hours'] + $countdown['diff_php']['minutes'] == 0)
);

// special option: show days only, recreate $assets array
if($params->get('compact_view', 0) == 1) {
	if($countdown['diff_php']['days'] > 0) {
		$visible = 'days';
	} elseif($countdown['diff_php']['hours'] > 0) {
		$visible = 'hours';
	} elseif($countdown['diff_php']['minutes'] > 0) {
		$visible = 'minutes';
	} else {
		$visible = 'seconds';
	}
	foreach($assets as $asset => $display) {
		$assets[$asset] = ($asset == $visible);
	}
}

// render module trigger javascript in module position, not in main document
// this is a workaround for caching software that supports "exclude position"
// settings. Rendering the script below directly in module position (not at the
// top of the page) ensures that it will be called on each page reload.
$js_library = $params->get('js_library', '');
$events_queue = $params->get('events_queue_json', '[]');
$options = $params->get('options_json', '{}');

if(empty($js_library)) { ?>
	<script>
		if(typeof(jQuery) != "undefined") {
			jQuery(document).ready(function() {
				scdpCounters.add(<?php echo $module->id; ?>, <?php echo $events_queue; ?>, <?php echo $options; ?>);
			});
		} else {
			window.addEvent("domready", function() {
				scdpCounters.add(<?php echo $module->id; ?>, <?php echo $events_queue; ?>, <?php echo $options; ?>);
			});
		}
	</script> <?php
} elseif($js_library == 1) { // mootools ?>
	<script>
			window.addEvent("domready", function() {
				scdpCounters.add(<?php echo $module->id; ?>, <?php echo $events_queue; ?>, <?php echo $options; ?>);
			});
	</script> <?php
} elseif($js_library == 2) { // jQuery ?>
	<script>
			jQuery(document).ready(function() {
				scdpCounters.add(<?php echo $module->id; ?>, <?php echo $events_queue; ?>, <?php echo $options; ?>);
			});
	</script> <?php
}
// end caching workaround ?>

<div id="scdpro<?php echo $ids_suffix; ?>" class="clearfix scdp-container<?php echo $moduleclass_sfx; ?>"<?php echo $layoutConfig['module_style']; ?>>
	<div<?php echo $layoutConfig['wrapper_class']; ?>>
		<?php if($layoutConfig['text_before_counter']): ?>
			<div id="scdpro_text<?php echo $ids_suffix; ?>" class="scdp-text-<?php echo $layoutConfig['text_layout_class']; ?>"<?php echo $layoutConfig['event_text_style']; ?>>
				<?php echo $countdown['text']; ?>
			</div>
			<?php if($layoutConfig['text_layout_class'] == '-vert') : ?>
				<div class="clear"></div>
			<?php endif; ?>
		<?php endif; ?>	
		
		<div class="clearfix action-counter scdp-counter-<?php echo $layoutConfig['text_layout_class']; ?>"<?php echo $layoutConfig['counter_style']; ?>>
			
			<?php foreach($assets as $name => $display): ?>
				<?php $unit_style = $layoutConfig['units_style'].(!$display ? 'display:none;' : ''); ?>
				<?php $unit_style = $unit_style ? ' style="'.$unit_style.'"' : ''; ?>
				<?php
					$isImage = false;
					if($labelTypes[$name] == '&') {
						$label_text = $layoutConfig['separator_image'];
						$isImage = true;
					}
					elseif($labelTypes[$name] == '%') {
						$label_text = null;
					}
					else {
						$label_text = $labelTypes[$name];
					}
				?>
				
				<div id="scdpro_<?php echo $name.$ids_suffix; ?>" class="scdp-unit-<?php echo $layoutConfig['counter_layout_class']; ?>"<?php echo $unit_style; ?>>
				<?php $number = modSmartCountdownHelper::getNumber($name, $values); ?>
				
				<?php
					// use special style (days_number_style) for days unit, otherwise general style (numbers_style)
					$number_style = $name == 'days' ? $layoutConfig['days_number_style'] : $layoutConfig['numbers_style'];
				?>
				<?php if($layoutConfig['labels_before_numbers']): ?>
				
					<div class="scdp-label scdp-label-<?php echo $layoutConfig['units_layout_class']; ?>"<?php echo $layoutConfig['labels_style']; ?>>
					<?php echo JHtml::_('SmartCDPro.label', $name, $number['value'], $label_text, $isImage); ?>
					</div>
					<div class="scdp-number scdp-number-<?php echo $layoutConfig['units_layout_class']; ?>"<?php echo $number_style; ?>>
					<?php echo JHtml::_('SmartCDPro.number', $number['digits'], $digitsConfig, $ids_suffix, $mode); ?>
					</div>
				<?php else: ?>
					<div class="scdp-number scdp-number-<?php echo $layoutConfig['units_layout_class']; ?>"<?php echo $number_style; ?>>
					<?php echo JHtml::_('SmartCDPro.number', $number['digits'], $digitsConfig, $ids_suffix, $mode); ?>
					</div>
					<div class="scdp-label scdp-label-<?php echo $layoutConfig['units_layout_class']; ?>"<?php echo $layoutConfig['labels_style']; ?>>
					<?php echo JHtml::_('SmartCDPro.label', $name, $number['value'], $label_text, $isImage); ?>
					</div>
				<?php endif;?>
				</div>
			<?php endforeach; ?>
			
		</div>
		
		<?php if(!$layoutConfig['text_before_counter']): ?>
			<?php if($layoutConfig['text_layout_class'] == '-vert') : ?>
				<div class="clear"></div>
			<?php endif; ?>
			<div id="scdpro_text<?php echo $ids_suffix; ?>" class="scdp-text-<?php echo $layoutConfig['text_layout_class']; ?>"<?php echo $layoutConfig['event_text_style']; ?>>
				<?php echo $countdown['text']; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<div class="clear"></div>
