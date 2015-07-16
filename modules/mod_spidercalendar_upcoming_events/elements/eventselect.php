<?php 
 /**
 * @package Spider Calendar
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

class JFormFieldEventSelect extends JFormFieldText
{

var	$_name = 'eventselect';
	function getInput()
	{        
		ob_start();
        static $embedded;
        if(!$embedded)
        {
            $embedded=true;
        }
		JHTML::_('behavior.modal', 'a.modal');
		$name = $this->name;
		$value = $this->value;
		
		
		$db	= JFactory::getDBO();
		?>

<script type="text/javascript">


var next=0;
function jSelectEvents(evid, title, day) {
	
	
		event_ids =document.getElementById('events').value;
		
		tbody = document.getElementById('event');
		
		var  str;
		str=document.getElementById('events').value;
		
	
       
		
		for(i=0; i<evid.length; i++)
		{
		var  var_serch=","+evid[i]+",";
		
			tr = document.createElement('tr');
				tr.setAttribute('event_id', evid[i]);
				
				tr.setAttribute('id', 'event_select_'+next);
				
			var td_info = document.createElement('td');
				td_info.setAttribute('id','info_'+next);
			//	td_info.setAttribute('width','60%');
			
			
			b = document.createElement('b');
				b.innerHTML = title[i];
				b.style.width='150px';
				b.style.float='left';
				b.style.position="inherit";
			
			
			td_info.appendChild(b);
			
		
			
		
			
			//td.appendChild(p_url);
			
			var img_X = document.createElement("img");
					img_X.setAttribute("src", "<?php echo JURI::root() ?>modules/mod_spidercalendar_upcoming_events/images/delete_el.png");
//					img_X.setAttribute("height", "17");
					img_X.style.cssText = "cursor:pointer;";
					img_X.setAttribute("onclick", 'remove_row("event_select_'+next+'")');
					
			var td_X = document.createElement("td");
					td_X.setAttribute("id", "X_"+next);
					td_X.setAttribute("valign", "middle");
//					td_X.setAttribute("align", "right");
					td_X.style.width='50px';
					td_X.appendChild(img_X);
					
			var img_UP = document.createElement("img");
					img_UP.setAttribute("src", "<?php echo JURI::root() ?>modules/mod_spidercalendar_upcoming_events/images/up.png");
//					img_UP.setAttribute("height", "17");
					img_UP.style.cssText = "cursor:pointer";
					img_UP.setAttribute("onclick", 'up_row("event_select_'+next+'")');
					
			var td_UP = document.createElement("td");
					td_UP.setAttribute("id", "up_"+next);
					td_UP.setAttribute("valign", "middle");
					td_UP.style.width='20px';
					td_UP.appendChild(img_UP);
					
			var img_DOWN = document.createElement("img");
					img_DOWN.setAttribute("src", "<?php echo JURI::root() ?>modules/mod_spidercalendar_upcoming_events/images/down.png");
//					img_DOWN.setAttribute("height", "17");
					img_DOWN.style.cssText = "margin:2px;cursor:pointer";
					img_DOWN.setAttribute("onclick", 'down_row("event_select_'+next+'")');
					
			var td_DOWN = document.createElement("td");
					td_DOWN.setAttribute("id", "down_"+next);
					td_DOWN.setAttribute("valign", "middle");
					td_DOWN.style.width='20px';
					td_DOWN.appendChild(img_DOWN);
				
			tr.appendChild(td_info);
			tr.appendChild(td_X);
			tr.appendChild(td_UP);
			tr.appendChild(td_DOWN);
			tbody.appendChild(tr);

//refresh
			next++;
			
		}
			
		document.getElementById('events').value=event_ids;
		
		
		SqueezeBox.close();
		refresh_();
		
	}
	
function remove_row(id){	

	tr=document.getElementById(id);
	tr.parentNode.removeChild(tr);
	refresh_();
}

function refresh_(){

	event=document.getElementById('event');
	GLOBAL_tbody=event;
	tox=',';
	for (x=0; x < GLOBAL_tbody.childNodes.length; x++)
	{
		tr=GLOBAL_tbody.childNodes[x];
		tox=tox+tr.getAttribute('event_id')+',';
	}

	document.getElementById('events').value=tox;
	
}

function up_row(id){
	form=document.getElementById(id).parentNode;
	k=0;
	while(form.childNodes[k])
	{
	if(form.childNodes[k].getAttribute("id"))
	if(id==form.childNodes[k].getAttribute("id"))
		break;
	k++;
	}
	if(k!=0)
	{
		up=form.childNodes[k-1];
		down=form.childNodes[k];
		form.removeChild(down);
		form.insertBefore(down, up);
		refresh_();
	}
}

function down_row(id){
	form=document.getElementById(id).parentNode;
	l=form.childNodes.length;
	k=0;
	while(form.childNodes[k])
	{
	if(id==form.childNodes[k].id)
		break;
	k++;
	}

	if(k!=l-1)
	{
		up=form.childNodes[k];
		down=form.childNodes[k+2];
		form.removeChild(up);
if(!down)
down=null;
		form.insertBefore(up, down);
		refresh_();
	}
}

</script>


<table>
<tr>
<td>
<a id="add" class="modal" href="index.php?option=com_spidercalendar&task=module_event&amp;tmpl=component&amp;object=id" rel="{handler: 'iframe', size: {x: 750, y: 450}}" 
onclick="addcal()">
<img src="<?php echo JURI::root() ?>modules/mod_spidercalendar_upcoming_events/images/add_but.png" /></a>

<table width="100%">
<tbody id="event"></tbody>
</table>
</td>
</tr>
</table>
<span id="req"></span>
<?php 
$query="SELECT MAX(version_id) FROM #__schemas";
$db->setQuery($query);
$version=$db->loadResult();


if((float)$version>3.1)
{
?>
<script>

function addcal()
{
var link = document.getElementById('add');

var calendar = document.getElementById('add').parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.childNodes[0].childNodes[1].childNodes[1].childNodes[5].value;

link.href='index.php?option=com_spidercalendar&task=module_event&tmpl=component&object=id&cal='+calendar;

}
</script> 
<?php
}
else
{
?>
<script>

function addcal()
{
var link = document.getElementById('add');

var calendar = document.getElementById('add').parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.childNodes[3].childNodes[3].childNodes[5].value;

link.href='index.php?option=com_spidercalendar&task=module_event&tmpl=component&object=id&cal='+calendar;

}
</script> 
<?php
}
?>

<input type="hidden" name="<?php echo $this->name ?>" id="events" size="80" value="<?php echo $this->value ?>" />
<?php

JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_spidercalendar/tables');


	$events=array();
	$events_id=explode(',',$this->value);
	$events_id= array_slice($events_id,1, count($events_id)-2);  
	
	

	foreach($events_id as $id)
	{

		$query ="SELECT * FROM #__spidercalendar_event  WHERE published='1' 
		AND id=".$id;
		$db->setQuery($query); 
		
		$is=$db->loadObject();
		if($is)
		$events[] = $db->loadObject();
		if ($db->getErrorNum())
		{
			echo $db->stderr();
			return false;
		}
		
	}



if($events)
{
	foreach($events as $event)
	{
	  $day = $event->date;
		$v_ids[]=$event->id;
	
		$v_titles[]=addslashes($event->title.' ('.date('d M Y',strtotime($day)).')');
	
		
	}
	

	$v_id='["'.implode('","',$v_ids).'"]';
	$v_title='["'.implode('","',$v_titles).'"]';
	

	//print_r ($v_title);
	?>
<script type="text/javascript">                
jSelectEvents(<?php echo $v_id?>,<?php echo $v_title?>,<?php echo $day?>);
<?php
}

?>

 </script>
 
 <span id="event_select"></span>
		<script type="text/javascript">
      var show0=document.getElementById('show0').checked;
	 var  show1=document.getElementById('show1').checked;
	if (show0 || show1)
	{
document.getElementById("event_select").parentNode.parentNode.setAttribute("style","display:none");

	}
	
	
        </script>
 
      <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

	}
}
?>