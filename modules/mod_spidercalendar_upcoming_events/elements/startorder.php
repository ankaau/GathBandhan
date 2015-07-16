<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldStartOrder extends JFormField
{
	var	$_name = 'startorder';
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

<fieldset class="radio">
<input type="radio" name="<?php echo $this->name;?>" value="0" <?php if($this->value==0)echo'checked="checked"'?> id="ord1" ><label for="ord1">Ordering</label>
<input type="radio" name="<?php echo $this->name;?>" value="1" <?php if($this->value==1)echo'checked="checked"'?> id="rand1"><label for="rand1">Random</label> 
</fieldset>

<span id="storder"></span>
<script type="text/javascript">
var show0=document.getElementById('show0').checked;
var show2=document.getElementById('show2').checked;
if(show0 || show2 )
{
document.getElementById('storder').parentNode.parentNode.setAttribute('style','display:none');
}
var showd0=document.getElementById('showd0').checked;
if(showd0)
{
document.getElementById('storder').parentNode.parentNode.setAttribute('style','display:none');
}

</script>



        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	}
	
	?>