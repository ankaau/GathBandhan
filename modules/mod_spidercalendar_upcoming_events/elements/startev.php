<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldStartEv extends JFormField
{
	var	$_name = 'startev';
function getInput()
{
	
        ob_start();
        static $embedded;
                if(!$embedded)
        {

            $embedded=true;

        }
        $name = $this->name;
		$value = $this->value;
		$node =  $this->element;



            ?>


<input type="text" name="<?php echo $this->name;?>" value="<?php echo $this->value;?>"/>
<span id="stev"></span>
<script type="text/javascript">
var show0=document.getElementById('show0').checked;
var show2=document.getElementById('show2').checked;

if(show0 || show2 )
{
document.getElementById('stev').parentNode.parentNode.setAttribute('style','display:none');

}
var showd0=document.getElementById('showd0').checked;
if(showd0)
{
document.getElementById('stev').parentNode.parentNode.setAttribute('style','display:none');
}

/*var showd1=document.getElementById('showd1').checked;
if(show1 && showd1)
{
if(document.getElementById('stev').parentNode.parentNode.childNodes[3].value=='')
alert('Field required: Events Qauntity');

}*/
</script>



        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	
	}
	
	?>