<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

	
class JFormFieldViewselect extends JFormField
{
	var	$_name = 'Viewselect';
function getInput()
{
	
        ob_start();
        static $embedded;
                if(!$embedded)
        {

            $embedded=true;

        }

$value=$this->value;
$name=$this->name;

$values=explode(',',$value);

array_pop($values);


?>

<div id="select_value"></div>

<div id="check">
<input onchange="changeView()"  <?php if(in_array('month',$values) or $value=="") echo 'checked="checked"' ?> type="checkbox" id="view_0" value="month"><span>Month &nbsp; </span>
<input onchange="changeView()" <?php if(in_array('list',$values) or $value=="") echo 'checked="checked"' ?> type="checkbox" id="view_1" value="list"><span>List &nbsp;</span>
<input onchange="changeView()" <?php if(in_array('week',$values) or $value=="") echo 'checked="checked"' ?> type="checkbox" id="view_2" value="week"><span>Week &nbsp;</span>
<input onchange="changeView()" <?php if(in_array('day',$values) or $value=="") echo 'checked="checked"' ?> type="checkbox" id="view_3" value="day"><span>Day &nbsp;</span>
</div>

<input type="hidden" id="view"  value="<?php echo $value ?>" name="<?php echo $name ?>" />

<script>

function changeView()
{
tox='';
for(i=0;i<=3;i++)
{

if(document.getElementById('view_'+i).checked)
{

tox=tox+document.getElementById('view_'+i).value+',';
}



}
document.getElementById('view').value=tox;


}

</script>

        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	}
	
	?>