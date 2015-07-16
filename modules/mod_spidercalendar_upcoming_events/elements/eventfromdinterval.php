<?php
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

class JFormFieldEventFromDInterval extends JFormField
{
	var	$_name = 'eventfromdinterval';
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
		
       ?>
	   

<fieldset class="radio">
<input type="radio" name="<?php echo $this->name;?>" value="0" <?php if($this->value==0)echo'checked="checked"'?>onChange="showd_(0)" id="showd0" ><label for="showd0">Current Date</label>
<input type="radio" name="<?php echo $this->name;?>" value="1" <?php if($this->value==1)echo'checked="checked"'?>onChange="showd_(1)" id="showd1"><label for="showd1">Start Date</label> 
</fieldset>


   

<span id="evd"></span>
<script type="text/javascript">
var show0=document.getElementById('show0').checked;
var show2=document.getElementById('show2').checked;
if(show0 || show2 )
{
document.getElementById('evd').parentNode.parentNode.setAttribute('style','display:none');
}
</script>

<?php 
		$db	= JFactory::getDBO();
$query="SELECT MAX(version_id) FROM #__schemas";
$db->setQuery($query);
$version=$db->loadResult();


if((float)$version>3.1)
{

?>
<script type="text/javascript">

function showd_(y)
{
  if(y==0)
  {
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[3].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[4].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[2].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].setAttribute('style','display:none');  
  }
  else
  {
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[3].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[4].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[5].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[6].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[7].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[8].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[0].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[1].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[2].removeAttribute('style'); 
document.getElementById('evtype').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].setAttribute('style','display:none'); 
  }

}

</script>
<?php
}
else
{

?>
<script type="text/javascript">
function showd_(y)
{
  if(y==0)
  {
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[7].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[9].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[11].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[13].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[15].removeAttribute('style');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[17].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[19].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[21].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[23].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[25].setAttribute('style','display:none');  
  }
  else
  {
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[7].setAttribute('style','display:none');
document.getElementById('evd').parentNode.parentNode.parentNode.childNodes[9].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[11].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[13].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[15].setAttribute('style','display:none');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[17].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[19].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[21].removeAttribute('style');
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[23].removeAttribute('style'); 
document.getElementById('evtype').parentNode.parentNode.parentNode.childNodes[25].setAttribute('style','display:none'); 
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