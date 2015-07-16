<?php
  
 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
 defined( '_JEXEC' ) or die( 'Restricted access' );

class JFormFieldBuyCommercialtext extends JFormFieldText
{
 	protected $type = 'BuyCommercialtext';

	public function getInput()
	{
                        return '
	<div style="font-size:16px;padding:20px; padding-right:50px;text-align:justify">
		<img src="../modules/mod_spidercalendar/elements/header.png" border="0" alt="www.web-dorado.com" width="215" align="left">
<p>If you want to adjust the colors and the design of the calendar to the style of your website, that is not a problem either. Web-Dorado offers a full version of Spider Calendar.</P><br />
		
		<div style="text-align:right;">
		<a href="http://web-dorado.com/files/fromSpiderCalendar.php" target="_blank" style="color:#00AEEF; text-decoration:underline; font-size:18px">
		Get Spider Calendar Full
		</a>
		</div>
		<br /><br />
	</div>';
    }
}
?>