<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldEventFromCurrentD extends JFormField
{
	var	$_name = 'eventfromcurrentd';
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
        //$required = $this->required;


            ?>
			
<input  type="text" name="<?php echo $this->name;?>" value="<?php echo $this->value;?>"  >		

<span id="evc"></span>
<script type="text/javascript">
var show1=document.getElementById('show1').checked;
var show2=document.getElementById('show2').checked;

if(show1 || show2)
{
document.getElementById('evc').parentNode.parentNode.setAttribute('style','display:none');

//document.getElementById('evc').parentNode.parentNode.childNodes[1].className='';
}





</script>




        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	

	}
	
	?>