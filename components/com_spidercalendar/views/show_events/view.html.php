<?php
 
 /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/


defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.application.component.view');

class spidercalendarViewShow_events extends JViewLegacy
{

    function display($tpl = null)

		{

			$model = $this->getModel();

			$result = $model->showNote();

			$this->assignRef( 'rows',	$result[0] );
			$this->assignRef( 'pageNav',$result[1] );
			$this->assignRef( 'lists',	$result[2] );
			$this->assignRef( 'option',	$result[3] );
			
			
			parent::display($tpl);

		}

}

?>