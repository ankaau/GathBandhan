<?php 
  
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class JFormFieldCalendarSelect extends JFormField
{

	var	$_name = 'calendarselect';

	function getInput()
	{
		
				ob_start();
        static $embedded;
        if(!$embedded)
        {
            $embedded=true;
        }
		$db = JFactory::getDBO();

		$query = 'SELECT id , title FROM #__spidercalendar_calendar WHERE published=1';
		$db->setQuery( $query );
		$options = $db->loadObjectList();
        $name = $this->name;
		$value = $this->value;
		$node =  $this->element;

	?>
	
	<?php 
$query="SELECT MAX(version_id) FROM #__schemas";
$db->setQuery($query);
$version=$db->loadResult();


if((float)$version>3.1)
{
?>

	
	<script>
	function selectcal()
	{

	var a = document.getElementById('list').parentNode.parentNode.parentNode.parentNode.childNodes[1].childNodes[3].childNodes[1].childNodes[3].childNodes[1].childNodes[0].childNodes[1].childNodes[1];
	var calendar=document.getElementById('list').parentNode.childNodes[5];
	
	var selectcalendarvalue = calendar.value;
	
    a.href=a.href+'&cal='+selectcalendarvalue;
}

	
	</script>
<?php
}
else
{
?>
	
	<script>
	function selectcal()
	{

	var a = document.getElementById('list').parentNode.parentNode.parentNode.childNodes[25].childNodes[3].childNodes[3].childNodes[1].childNodes[0].childNodes[1].childNodes[1];
	var calendar=document.getElementById('list').parentNode.parentNode.childNodes[3].childNodes[5];
	
	var selectcalendarvalue = document.getElementById('list').parentNode.parentNode.childNodes[3].childNodes[5].value;
	
    a.href=a.href+'&cal='+selectcalendarvalue;
}

	
	</script>
<?php
}
?>


	<span id="list"></span>
	
<select name="<?php echo $this->name ;?>" onchange="selectcal()" class="inputbox" size="1">
<option value="">-Select Calendar-</option>
    <?php
		
	 for($i=0, $n=count($options); $i < $n ; $i++)
	{
      $row = $options[$i];
	
    ?>
<option  value="<?php echo $row->id; ?>" <?php if ($this->value==$row->id) echo 'selected="selected"'; ?>><?php echo $row->title;?></option>
        <?php
	}
	?>
    </select> 
	
		
		
		
	<?php	
		  
	
        $content=ob_get_contents();

        ob_end_clean();
        return $content;
}
}
?>