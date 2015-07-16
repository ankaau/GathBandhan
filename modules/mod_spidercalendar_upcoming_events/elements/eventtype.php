<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldEventType extends JFormField
{
	var	$_name = 'eventtype';
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


<fieldset  class="radio" >
<input type="radio" name="<?php echo $this->name;?>"  value="0" <?php if($this->value==0) echo 'checked="checked"'?> onChange="show_(0)" id="show0"><label for="show0" > Events Starting From Current Date</label>
<input type="radio" name="<?php echo $this->name;?>"  value="1" <?php if($this->value==1) echo 'checked="checked"'?> onChange="show_(1)" id="show1"><label for="show1" > Events In Date Interval </label>
<input type="radio" name="<?php echo $this->name;?>"  value="2" <?php if($this->value==2) echo 'checked="checked"'?> onChange="show_(2)" id="show2" ><label for="show2" >Selected Events</label>
</fieldset>

<span id="evtype"></span>



<?php 
		$db	= JFactory::getDBO();
$query="SELECT MAX(version_id) FROM #__schemas";
$db->setQuery($query);
$version=$db->loadResult();


if((float)$version>3.1)
{
?>
<script type="text/javascript">

function show_(x)
{
if(x==0)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[3].removeAttribute('style');

document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[4].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[2].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].setAttribute('style','display:none');
}

 else if(x==1)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[3].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[4].removeAttribute('style');

var showd1=document.getElementById('showd1').checked;
var showd0=document.getElementById('showd0').checked;
if(showd1)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].removeAttribute('style');

}
if(showd0)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[2].setAttribute('style','display:none');

}


}

else
{
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[3].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[4].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[2].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].removeAttribute('style');
}

}
</script>

<?php
}
else
{
?>
<script type="text/javascript">
function show_(x)
{
if(x==0)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[7].removeAttribute('style');

document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[9].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[11].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[13].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[15].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[17].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[19].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[21].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[23].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[25].setAttribute('style','display:none');
}

 else if(x==1)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[25].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[9].removeAttribute('style');

var showd1=document.getElementById('showd1').checked;
var showd0=document.getElementById('showd0').checked;
if(showd1)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[11].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[13].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[15].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[17].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[19].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[21].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[23].removeAttribute('style');

}
if(showd0)
{
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[11].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[13].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[15].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[17].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[19].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[21].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[23].setAttribute('style','display:none');

}


}

else
{
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[9].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[11].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[13].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[15].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[17].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[19].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[21].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[23].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[25].removeAttribute('style');
}

}

</script>
<?php
}
?>







        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	}
	
	?>