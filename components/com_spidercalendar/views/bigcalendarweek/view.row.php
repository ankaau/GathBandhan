<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/


defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view');







class spidercalendarViewbigcalendarweek extends JViewLegacy

{

    function display($tpl = null)

		{

			$model = $this->getModel();

			$result = $model->getdays();

			$this->assignRef( 'all_array_days',	$result[0] );
			
			$this->assignRef( 'all_title',	$result[1] );

			$this->assignRef( 'option',	$result[2] );

			$this->assignRef( 'all_array_days1',	$result[3] );
			
			$this->assignRef( 'calendar',	$result[4] );
			$this->assignRef( 'all_ev_ids',	$result[5] );
			$result1 = $model->getcategories();
			$this->assignRef( 'categories',	$result1[0] );
		

			parent::display($tpl);

		}

}

?>