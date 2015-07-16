<?php 
  
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class JFormFieldcalendarselect extends JFormField
{

	var	$_name = 'calendarselect';

	function getInput()
	{
		
		
		$db =& JFactory::getDBO();

		$query = 'SELECT id, title FROM #__spidercalendar_calendar WHERE published=1';
		$db->setQuery( $query );
		$options = $db->loadObjectList();
        $name = $this->name;
		$value = $this->value;
		$node =  $this->element;
		$control_name = $this->options['title'];
		$id=  $this->id;
		
		
		
		
		array_unshift($options, JHTML::_('select.option', '-1', ''.JText::_('Select Calendar').'', 'id', 'title', $disable=true ));
		return JHTML::_('select.genericlist', $options, $this->name, $control_name, 'id', 'title', $this->value );
		
	
	}
}
?>