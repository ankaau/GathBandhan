<?php
 
 /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/



defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view');




class spidercalendarViewspidercalendar extends JViewLegacy
{

    function display($tpl = null)
		{

			$model = $this->getModel();

			$result = $model->showcalendar();

			$this->assignRef( 'rows',	$result[0] );

			$this->assignRef( 'option',	$result[1] );
						
			$this->assignRef( 'eventIDs',	$result[2] );
			parent::display($tpl);

		}

}

?>