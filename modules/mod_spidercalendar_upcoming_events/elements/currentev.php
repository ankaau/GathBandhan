<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldCurrentEv extends JFormField
{
	var	$_name = 'currentev';
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
        $required = $this->required;
		
            ?>



<input type="text" name="<?php echo $this->name;?>" value="<?php echo $this->value;?>"/>
<span id="currev"></span>
<script type="text/javascript">
var show0=document.getElementById('show0').checked;
var show2=document.getElementById('show2').checked;

if(show0 || show2 )
{
document.getElementById('currev').parentNode.parentNode.setAttribute('style','display:none');


}

var showd1=document.getElementById('showd1').checked;
if(showd1)
{
document.getElementById('currev').parentNode.parentNode.setAttribute('style','display:none');
}





</script>

        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	
	}
	
	?>