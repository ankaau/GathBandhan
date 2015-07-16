<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldStartCalendar extends JFormField
{
	var	$_name = 'startcalendar';
function getInput()
{
	
        ob_start();
        static $embedded;
                if(!$embedded)
        {

            $embedded=true;

        }
		JHTML::_('behavior.calendar');
	    $name = $this->name;
		$value = $this->value;
		$node =  $this->element;

       echo JHTML::_('calendar', $value, $name,'0000-00-00', '%Y-%m-%d');
	   
	   ?>




<span id="stcal"></span>
<script type="text/javascript">


var show0=document.getElementById('show0').checked;
var show2=document.getElementById('show2').checked;

if(show0 || show2 )
{
document.getElementById('stcal').parentNode.parentNode.setAttribute('style','display:none');

}
var showd0=document.getElementById('showd0').checked;
if(showd0)
{
document.getElementById('stcal').parentNode.parentNode.setAttribute('style','display:none');

}
/*var showd1=document.getElementById('showd1').checked;
if(show1 && showd1)
{
if(document.getElementById('stcal').parentNode.parentNode.childNodes[3].value=='')
alert('Field required: Select Start Date');

}*/

</script>

        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	
	}
	
	?>