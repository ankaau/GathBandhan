<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );





class TOOLBAR_spidercalendar {




public static function _NEW_calendar() {
		JToolBarHelper::title( JText::_( 'Add Calendar' ), 'generic.png' );
		JToolBarHelper::apply('apply_calendar');
		JToolBarHelper::save('save_calendar');
		JToolBarHelper::custom( 'event_save_show', 'edit.png', '', 'Manage Events', '', '' );
		JToolBarHelper::cancel('cancel_calendar');		
	}

	public static function _DEFAULT_calendar() {

		JToolBarHelper::title( JText::_( 'Calendar Manager' ), 'generic.png' );
		//JToolBarHelper::preferences('com_spiderfc', '550','800','Preferences','/administrator/components/com_spiderfc/component_config.xml');
		JToolBarHelper::addNew('add_calendar');
		JToolBarHelper::editList('edit_calendar');
		JToolBarHelper::publishList('publish_calendar');
		JToolBarHelper::unpublishList('unpublish_calendar');
		JToolBarHelper::deleteList('Are you sure you want to delete?', 'remove_calendar');
		
		
	}
	
		
	public static function _NEW_event_category() {
			JToolBarHelper::apply('apply_event_category');
		JToolBarHelper::save('save_event_category');

		JToolBarHelper::cancel('cancel_event_category');		
	}

	public static function _DEFAULT_event_category() {

			JToolBarHelper::addNew('add_event_category');
					JToolBarHelper::editList('edit_event_category');
		JToolBarHelper::title( JText::_( 'Category Manager' ), 'generic.png' );
		JToolBarHelper::publishList('publish_event_category');
		JToolBarHelper::unpublishList('unpublish_event_category');
		JToolBarHelper::deleteList('Are you sure you want to delete?', 'remove_event_category');


	}
	
	
	
	public static function _NEW_theme() {
		JToolBarHelper::title( JText::_( 'Add Theme' ), 'generic.png' );
		JToolBarHelper::apply('apply_theme');
		JToolBarHelper::save('save_theme');
		
		JToolBarHelper::cancel('cancel_theme');		
	}

	public static function _DEFAULT_theme() {

		JToolBarHelper::title( JText::_( 'Calendar Manager' ), 'generic.png' );
		//JToolBarHelper::preferences('com_spiderfc', '550','800','Preferences','/administrator/components/com_spiderfc/component_config.xml');
		JToolBarHelper::addNew('add_theme');
		JToolBarHelper::editList('edit_theme');

		JToolBarHelper::deleteList('Are you sure you want to delete?', 'remove_theme');
		
		
	}
	

	

	public static function _NEW_event() {
		JToolBarHelper::title( JText::_( 'Add Event' ), 'generic.png' );
		JToolBarHelper::apply('apply_event');
		JToolBarHelper::save('save_event');
		
		JToolBarHelper::cancel('cancel_event');		
	}

	public static function _DEFAULT_event() {
		JToolBarHelper::title( JText::_( 'Manage Events' ), 'generic.png' );
		//JToolBarHelper::preferences('com_spiderfc', '550','800','Preferences','/administrator/components/com_spiderfc/component_config.xml');
		JToolBarHelper::addNew('add_event');
		JToolBarHelper::editList('edit_event');
		JToolBarHelper::publishList('publish_event');
		JToolBarHelper::unpublishList('unpublish_event');
		JToolBarHelper::deleteList('Are you sure you want to delete?', 'remove_event');
		JToolBarHelper::custom( 'back', 'back.png', '', 'Back', '', '' );
	}
	
		public static function _DEFAULT_plugin()
	{
	JToolBarHelper::title( JText::_( 'Plugin Code Generator' ), 'generic.png' );
	}


}


?>