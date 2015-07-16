<?php
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class spidercalendarViewadd_event extends JViewLegacy
{

    function display($tpl = null)

		{
	$model = $this->getModel();	$result = $model->addNote();	
	$this->assignRef( 'lists',	$result[0] );
	$this->assignRef( 'categories',	$result[1] );
	$this->assignRef( 'row',	$result[2] );			parent::display($tpl);

		}

}

?>