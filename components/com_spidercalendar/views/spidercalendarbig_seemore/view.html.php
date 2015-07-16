<?php
  /** * @package Spider Calendar lite * @author Web-Dorado * @copyright (C) 2011 Web-Dorado. All rights reserved. * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html **/



defined( '_JEXEC' ) or die( 'Restricted access' );



jimport( 'joomla.application.component.view');




class spidercalendarViewspidercalendarbig_seemore extends JViewLegacy
{

    function display($tpl = null)
		{

			$model = $this->getModel();

			$result = $model->showevent();

			$this->assignRef( 'rows',	$result[0] );

			$this->assignRef( 'option',	$result[1] );
				$result1 = $model->showcategoryevent();					    $this->assignRef( 'rows1',	$result1[0] );			$this->assignRef( 'option',	$result1[1] );						$this->assignRef( 'catcolors',	$result1[2] );			
		
			
			
		

			parent::display($tpl);

		}

}

?>