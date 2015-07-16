<?php
 
 /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/



defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view');




class spidercalendarViewspidercalendarbig extends JViewLegacy
{

    function display($tpl = null)
		{

			$model = $this->getModel();

			$result = $model->showevent();		
			$this->assignRef( 'row',	$result[0] );
			$this->assignRef( 'option',	$result[1] );
		
			
			
		

			parent::display($tpl);

		}

}

?>