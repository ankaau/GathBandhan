<?php
  
 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
 defined( '_JEXEC' ) or die( 'Restricted access' );

class JFormFieldBuyCommercial extends JFormFieldText
{
 	protected $type = 'BuyCommercial';

	public function getInput()
	{
                        return '
	<div style="font-size:16px;padding:20px; padding-right:50px;text-align:justify">

		<p><img style="position: relative;left: -190px;border:2px solid red" src="../modules/mod_spidercalendar/elements/ModuleParams.jpg" alt="" width="" height="" /></p>
	</div>';
    }
}
?>