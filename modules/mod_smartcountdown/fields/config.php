<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 -3.0
 * @version 2.1: config.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');

class JFormFieldConfig extends JFormFieldList
{
	public $type = 'config';
	
	public function getInput()
	{
		$html = array();
		
		$html[] = parent::getInput();
		$fx_dir = JPATH_ROOT.'/modules/mod_smartcountdown/fx';
		
		if($this->value && JFile::exists($fx_dir.'/'.$this->value)) {
			// if a preset is selected read the file and include its content as a hidden field
			// (for customconfig control)
			$contents = JFile::read($fx_dir.'/'.$this->value);
			$html[] = '<input id="scdp_config_text" type="hidden" value=\''.$contents.'\'>';
		}
		
		return implode("\n", $html);
	}
	
	public function getOptions()
	{		
		$options = array();
		$fx_dir = JPATH_ROOT.'/modules/mod_smartcountdown/fx';
		
		$items = JFolder::files($fx_dir, '\.xml$', true, false);
		
		// Build the field options.
		if (!empty($items))
		{
			foreach ($items as $item)
			{	
				$options[] = JHtml::_('select.option', '/'.$item, ucfirst(str_replace('_', ' ', substr($item, 0, -4))));
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
		
	}
}
