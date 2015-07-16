<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
defined('_JEXEC') or die('Restricted access');

	


class JFormFieldDefaultview extends JFormField
{
	var	$_name = 'Defaultview';
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
?>

<select name="<?php echo $name?>">
<option  <?php if($value=='month') echo 'selected="selected"'?> value='month'>Month</option>
<option <?php if($value=='list') echo 'selected="selected"'?> value='list'>List</option>
<option <?php if($value=='week') echo 'selected="selected"'?>  value='week'>Week</option>
<option <?php if($value=='day') echo 'selected="selected"'?> value='day'>Day</option>
</select>





        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	}
	
	?>