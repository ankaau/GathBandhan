<?php
/**
 * @package Module Smart Countdown for Joomla! 2.5 -3.0
 * @version 2.2.9: sessiondeadline.php
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('groupedlist');

/*
 * Only show a limited set of options if companion plugin is not installed or enabled
 */
class JFormFieldSessionDeadline extends JFormFieldGroupedList
{
	public $type = 'sessiondeadline';
	
	protected function getGroups()
	{
		$groups = parent::getGroups();
		if(!self::isPluginEnabled())
		{
			// this groupedlist is defined in config XML as two top-level
			// options ("no" and "count up from session start") and
			// a group with several countdown mode options. We have to
			// remove all options in this group if comanion plugin is
			// not enabled
			foreach($groups as $label => $group)
			{
				if(!is_integer($label))
				{
					// non-integer label means a group title, so this is the
					// group to remove. All options are elements of the group
					// array, so unsetting the group will delete the options
					unset($groups[$label]);
				}
			}
		}
		return $groups;
	}
	
	private static function isPluginEnabled()
	{
		$scsession_plugin = JPluginHelper::getPlugin('system', 'scsession');
		return !empty($scsession_plugin) ? true : false;
	}
}
