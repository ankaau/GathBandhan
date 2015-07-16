<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
 defined( '_JEXEC' ) or die( 'Restricted access' );

class HTML_contact
{	
	public static function Month_num($month_name)
	{

		for( $month_num=1; $month_num<=12; $month_num++ )

		

		{  

		    if (date( "F", mktime(0, 0, 0, $month_num, 1, 0 ) ) == $month_name)

		    

		    {  

			return $month_num;  

			

		    } 

		     

		}

		

	}
	public static function Month_name($month_num)
	{

		$timestamp = mktime(0, 0, 0, $month_num, 1, 2005);

		return date("F", $timestamp); 

	}
	
public static function show_event_category(&$rows, &$pageNav, &$lists){

JHtml::_('behavior.tooltip');


			
		?>
	<script type="text/javascript">
	
	 Joomla.submitbutton=function(pressbutton) 
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel_event_category') 
		{
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
	
Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order_event_category.value     = order;
    form.filter_order_Dir_event_category.value = dir;
    submitform( task );
}
	</script>
	
		<form action="index.php?option=com_spidercalendar&task=event_category" method="post" name="adminForm" id="adminForm">
        <a  style="float:right;color: red;font-size: 19px;text-decoration: none;" target="_blank" href="http://web-dorado.com/products/joomla-calendar.html" >
	<img style="float:right" src="components/com_spidercalendar/elements/header.png" /><br>
	<span style="padding-left:25px;">Get the full version  </span>
	</a>
	<p style="font-size:14px">
	<a href="http://web-dorado.com/spider-calendar-guide-step-3.html" target="_blank" style="color:blue;text-decoration:none">User Manual</a><br>
	This section allows you to create/edit the event categories.<br>
	You can add unlimited number of categories.
	<a href="http://web-dorado.com/spider-calendar-guide-step-3.html" target="_blank" style="color:blue;text-decoration:none">More...</a>
</p>
		<table>
		<tr>
			<td align="left" width="100%">
				<?php echo JText::_( 'Filter' ); ?>:
				<input type="text" name="search" id="search" value="<?php echo $lists['search_event_category'];?>" 
class="text_area" onchange="document.adminForm.submit();" />
				<button onclick="this.form.submit();"> <?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search').value='';this.form.submit();">
<?php echo JText::_( 'Reset' ); ?></button>
			</td>
		</tr>
		</table>    
    
        
    <table class="table table-striped">
    <thead>
    	<tr>
		    <th width="30" class="title"><?php echo "#" ?></td>
        	<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)"></th>
            <th width="50" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			
            <th><?php echo JHTML::_('grid.sort',   'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
            <th><?php echo JHTML::_('grid.sort',   'Color', 'color', @$lists['order_Dir'], @$lists['order'] ); ?></th>
           
		   <th width="50" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Published', 'published',@$lists['order_Dir'], @$lists['order'] ); ?></th>
	   </tr>
    </thead>
	<tfoot>
		<tr>
			<td colspan="11">
			 <?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
                
      <?php
	
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
$row = &$rows[$i];
$checked 	= JHTML::_('grid.id', $i, $row->id);
//$published 	= JHTML::_('grid.published', $row, $i, ); 
	$published 	= published( $row, $i, 'event_category'); 
 	
// prepare link for id column
$link = JRoute::_( 'index.php?option=com_spidercalendar&task=edit_event_category&cid[]='. $row->id );
?>
<tr class="<?php echo "row$k"; ?>">
<td align="center"><?php echo $i+1?></td>
<td><?php echo $checked ?></td>
<td><a href="<?php echo $link; ?>"><?php echo $row->id ?></a></td>
<td><a href="<?php echo $link; ?>"><?php echo $row->title ?></a></td>            
<td><a href="<?php echo $link; ?>"><?php echo $row->color ?></a></td>            
<td><?php echo $published ?></td>            
</tr>
        <?php
$k = 1 - $k;
	}
	?>
    </table>
    <input type="hidden" name="task" value="event_category" />
    <input type="hidden" name="option" value="com_spidercalendar">
       
    <input type="hidden" name="boxchecked" value="0"> 
	<input type="hidden" name="filter_order_event_category" 
value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir_event_category" value="<?php echo $lists['order_Dir']; ?>" />       
    </form>
    <?php	
	}

public static function edit_event_category(&$lists, &$row){
		
		JRequest::setVar( 'hidemainmenu', 1 );
	$editor	=JFactory::getEditor();
				?>
				
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel_event_category') {
		submitform( pressbutton );
		return;
		}
							
		submitform( pressbutton );
		
		}

			
        </script>    

<script type="text/javascript" src="components/com_spidercalendar/jscolor/jscolor.js"></script>

		<form action="index.php?option=com_spidercalendar&task=event_category" method="post" name="adminForm" id="adminForm">
	<div class="width-45 fltlft ">
	<fieldset class="adminform" >
		<legend>Category title</legend>	
	
<table class="admintable" >
          
              <tr>
                <td class="key" ><label for="message"><?php echo JText::_( 'Title' ); ?>:</label>  </td>
               <td> <input   type="text" name="title" value="<?php echo htmlspecialchars($row->title); ?>" /> </td>
               </tr>
               
				 <tr>
			   <td class="key" ><label for="message"><?php echo JText::_( 'Category Color' ); ?>:</label>  </td>
             
               <td><input type="text" name="color" id="color" style="width:107px;" value="<?php echo htmlspecialchars($row->color); ?>" class="color"/></td>
               </tr>
                

			    <tr>
					<td class="key"><label for="message"> <?php echo JText::_( 'Description' ); ?>:</label></td>
					<td ><?php echo $editor->display('description',$row->description,'100%','250','40','6');?></td>	
                        
				</tr>		
							
					<tr>
				<td class="key" ><label for="message"><?php echo JText::_( 'Published' ); ?>:</label>  </td>
               
			    <td ><?php echo $lists['published']; ?> </td>
                 </tr> 		
			
                </table>
                
     </fieldset >  
       </div>              
<input type="hidden" name="option" value="com_spidercalendar" />
<input type="hidden" name="task" value="event_category" />  
<input type="hidden" name="id" value="<?php echo $row->id?>" />        
<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
       
</form>
				<?php		
			   
		}	




	
 function plugin($calendars)
{
?>

<script>
function generate_code()
{

calendar=document.getElementById('calendar').value;

view=document.getElementById('view').value;
plg_code=document.getElementById('plg_code');
tox='';
for(i=0;i<=3;i++)
{

if(document.getElementById('view_'+i).checked)
{

tox=tox+document.getElementById('view_'+i).value+',';
}
}



plg_code.value='{loadspidercalendar calendar='+calendar+' view='+view+' views='+tox+'}';

}
</script>

<table class="admintable">
                  <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Select Calendar' ); ?>:
						</label>
					</td>
                	<td>
                    	<select id="calendar" style="width:150px;" >
						<option value="">Select Calendar</option>
						<?php
						foreach($calendars as $calendar)
						{
						echo '<option value="'.$calendar->id.'">'.$calendar->title.'</option>';
						}
						?>
						</select>

                    </td>
				</tr> 
				
				
			
				
					
				<tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Select View' ); ?>:
						</label>
					</td>
                	<td>
                    	<select id="view" style="width:150px;" >
						<option value="">Select View</option>
						<option value="month">Monthly</option>
						<option value="list">List</option>
						<option value="week">Weekly</option>
						<option value="day">Daily</option>
						
						</select>

                    </td>
				</tr> 


								<tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Select Displayed Views' ); ?>:
						</label>
					</td>
                	<td>
                    	
<input type="checkbox" id="view_0" value="month">Month
<input  type="checkbox" id="view_1" value="list">List
<input  type="checkbox" id="view_2" value="week">Week
<input  type="checkbox" id="view_3" value="day">Day

						
				

                    </td>
				</tr> 
							
				
				<tr>
					<td class="key">
						<img style="cursor:pointer" onclick="generate_code()" src="./components/com_spidercalendar/elements/generate.png" />
					</td>
                	<td>
                    	<input type="text" id="plg_code" style="width:400px" size="80" />
						
						
						</select>

                    </td>
				</tr> 
				
				
				 
				
				
				</table>


<?php



}	
				
		
		
	public static function addNote($lists, $categories, $row)
	{		
		JToolBarHelper::title( JText::_( 'Add an event for calendar' ).' <font style="color:red">'.$lists['calendar_name'].'</font>', 'generic.png' );
		JHTML::_('behavior.tooltip');	
		JHTML::_('behavior.calendar');
		JRequest::setVar( 'hidemainmenu', 1 );
		$editor	=JFactory::getEditor();
		$user =JFactory::getUser();
jimport('joomla.form.form');

$form = JForm::getInstance('naForm',dirname(__FILE__)."/datefields.xml");

		?><script language="javascript" type="text/javascript">
window.addEvent('domready', function() {Calendar.setup({
				inputField: "date",
				ifFormat: "%Y-%m-%d",
				button: "date_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});
				
				window.addEvent('domready', function() {Calendar.setup({
				inputField: "de_d",
				ifFormat: "%Y-%m-%d",
				button: "de_d_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});

		<!--
		Joomla.submitbutton= function (pressbutton) 

		{

			var form = document.adminForm;

			if (pressbutton == 'cancel_event') 

			{

				submitform( pressbutton );

				return;

			}
			
			
		if(form.date.value.search(/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/))
		{
			  alert('Invalid Date');
		}
		else
		if(form.selhour_from.value=="" && form.selminute_from.value=="" && form.selhour_to.value=="" && form.selminute_to.value=="")
			submitform( pressbutton );
			else
			if(form.selhour_from.value!="" && form.selminute_from.value!="" && form.selhour_to.value=="" && form.selminute_to.value=="")
				submitform( pressbutton );
				else
				if(form.selhour_from.value!="" && form.selminute_from.value!="" && form.selhour_to.value!="" && form.selminute_to.value!="")
					submitform( pressbutton );
					else
					alert('Invalid Time');
		}
		function checkhour(id)
			{	
				if(typeof(event)!='undefined')
				{
					var e = event; // for trans-browser compatibility
					var charCode = e.which || e.keyCode;
		
						if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
		
						hour=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
						hour=parseFloat(hour);
						if(document.getSelection()!='')
							return true;
						if((hour<0) || (hour>23))
						return false;
				}
						return true;
			}
		
		
		
		function checknumber(id)
			{	
				if(typeof(event)!='undefined')
				{
					var e = event; // for trans-browser compatibility
					var charCode = e.which || e.keyCode;
		
						if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
		
				}
						return true;
			}
		
		
		function checkminute(id)
			{	
			if(typeof(event)!='undefined')
				{
				var e = event; // for trans-browser compatibility
				var charCode = e.which || e.keyCode;
		
				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
		
					minute=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
					minute=parseFloat(minute);
					if(document.getSelection()!='')
							return true;
					if((minute<0) || (minute>59))
				return false;
				}
						return true;
			}		
		function add_0(id)
		{
		
		    input=document.getElementById(id);
		
		    if(input.value.length==1)
		
		    {
		
			input.value='0'+input.value;
		
			input.setAttribute("value", input.value);
		
		    }
		
		}
	
function check12hour(id)
			{	
				if(typeof(event)!='undefined')
				{
					var e = event; // for trans-browser compatibility
					var charCode = e.which || e.keyCode;
						input=document.getElementById(id);
		
		 
						if(charCode==48 && input.value.length==0)
						return false;
						
						if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
		
						hour=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
						hour=parseFloat(hour);
						if(document.getSelection()!='')
						return true;
						
						if((hour<0) || (hour>12))
						return false;
				}
						return true;
			}
		
		function change_type(type)
{
	if(document.getElementById('daily1').value=='')
		document.getElementById('daily1').value=1;
	else
		document.getElementById('repeat_input').removeAttribute('style');
	
	if(document.getElementById('weekly1').value=='')
		document.getElementById('weekly1').value=1;
	
	if(document.getElementById('monthly1').value=='')
		document.getElementById('monthly1').value=1;
	
	if(document.getElementById('yearly1').value=='')
		document.getElementById('yearly1').value=1;
		
		
	
			
	switch(type)
{
	
	
	case 'no_repeat':	
document.getElementById('daily').setAttribute('style','display:none');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('year_month').setAttribute('style','display:none');
//document.getElementById('repeat_input').value=1;
document.getElementById('month').value='';
document.getElementById('de_d').value=''

document.getElementById('repeat_until').setAttribute('style','display:none');
break;

case 'daily':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('repeat').innerHTML='Day(s)';
document.getElementById('repeat_input').value=document.getElementById('daily1').value;
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('daily1')};


break;

case 'weekly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('weekly').removeAttribute('style');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('repeat').innerHTML='Week(s) on :'
document.getElementById('repeat_input').value=document.getElementById('weekly1').value;
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('weekly1')};

break;

case 'monthly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').removeAttribute('style');
document.getElementById('repeat').innerHTML='Month(s)'
document.getElementById('repeat_input').value=document.getElementById('monthly1').value;
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('monthly1')};

break;

case 'yearly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('year_month').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').removeAttribute('style');
document.getElementById('repeat').innerHTML='Year(s) in '
document.getElementById('repeat_input').value=document.getElementById('yearly1').value;
document.getElementById('month').value='';
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('yearly1')};

break;

	
	
}
		
}

function week_value()
{
var value='';
for(i=1; i<=7; i++)
{

if (document.getElementById('week_'+i).checked)
{
	value=value+document.getElementById('week_'+i).value+',';
	
}
	
}
document.getElementById('week').value=value;



}


	

function input_repeat()
{
	if(document.getElementById('repeat_input').value==1)
	{
	document.getElementById('repeat_input').value='';
	
	}
	document.getElementById('repeat_input').removeAttribute('style');
	
}
		
function radio_month()
{
	if(document.getElementById('radio1').checked==true)
		{	
		document.getElementById('monthly_list').disabled=true;
		document.getElementById('month_week').disabled=true;
		document.getElementById('month').disabled=false;
		}
	else
	{
	document.getElementById('month').disabled=true;
	document.getElementById('monthly_list').disabled=false;
		document.getElementById('month_week').disabled=false;
	}
	

}
	
	
	function input_value(id)
	
{
	document.getElementById(id).value=document.getElementById('repeat_input').value;
}
		
		//-->

		</script>        
        <?php 
		$calendar =JTable::getInstance('spidercalendar_calendar', 'Table');
		// load the row from the db table
		$calendar->load( $lists['calendar']);
		
		
		?>
        <form  action="index.php" method="post" name="adminForm" id="adminForm">
		<input type="hidden" id="selday" name="selday" value="<?php echo date("d")?>" />
        <input type="hidden" id="selmonth" name="selmonth" value="<?php echo  date("m")?>" />
        <input type="hidden" id="selyear" name="selyear" value="<?php echo date("Y")?>" />
		

<div class="span5" >

<legend>
Event Details
</legend>
                
                <table class="admintable">
                  <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Title' ); ?>:
						</label>
					</td>
                	<td>
                    	<input type="text" id="title" name="title" size="41" />
                    </td>
				</tr> 
                
								 <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Select Category' ); ?>:
						</label>
					</td>
					
                	<td>
                    	<select id="category" name="category" style="width:233px" >
						<option>--Select Category--</option>
						<?php 
						foreach($categories as $category)
						{?>
						<option value="<?php echo $category->id?>" <?php if ($category->id==$row->category) echo 'selected="selected"';?>><?php echo $category->title?></option>
						
						
					<?php	}
						?>
						
						</select>
                    </td>
				</tr> 
				
				
                <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Date' ); ?>:
						</label>
					</td><td><?php $form->setValue('date',null,''); echo $form->getInput('date'); ?><img class="calendar" id="date_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px;margin-left:5px;" /></td></tr>      
                                   
                                  <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Time' ); ?>:
						</label>
					</td>
                                      <?php if($calendar->time_format==1){  ?>
                                        <td>
										 
                                    <input type="text" id="selhour_from" name="selhour_from" size="1" style="text-align:right;width:30px" onkeypress="return check12hour('selhour_from')" value="" title="from"   /> <b>:</b>
                                    <input type="text" id="selminute_from" name="selminute_from" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_from')" value="" onblur="add_0('selminute_from')"  title="from" /> 
									
									
									<select id="select_from" style="width:60px" name="select_from" >
									<option selected="selected">AM</option>
									<option>PM</option>
									
									</select>
									
									
									<span style="font-size:12px">&nbsp;-&nbsp;</span>
                                    
									
									<input type="text" id="selhour_to" name="selhour_to" size="1" style="text-align:right;width:30px" onkeypress="return check12hour('selhour_to')" value=""  title="to" /> <b>:</b>
                                    <input type="text" id="selminute_to" name="selminute_to" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_to')" value="" onblur="add_0('selminute_to')"  title="to" />
                                   
								   <select id="select_to" style="width:60px" name="select_to">
									<option>AM</option>
									<option>PM</option>
									
									</select>
									
									</td>
				<?php } if($calendar->time_format==0){?>
				
				<td>
										 
                                    <input type="text" id="selhour_from" name="selhour_from" size="1" style="text-align:right;width:30px" onkeypress="return checkhour('selhour_from')" value="" title="from" onblur="add_0('selhour_from')"  /> <b>:</b>
                                    <input type="text" id="selminute_from" name="selminute_from" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_from')" value=""  title="from" onblur="add_0('selminute_from')"/> 
									
									
									<span style="font-size:12px">&nbsp;-&nbsp;</span>
                                    
									
									<input type="text" id="selhour_to" name="selhour_to" size="1" style="text-align:right;width:30px" onkeypress="return checkhour('selhour_to')" value=""  title="to" onblur="add_0('selhour_to')"/> <b>:</b>
                                    <input type="text" id="selminute_to" name="selminute_to" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_to')" value=""  title="to" onblur="add_0('selminute_to')"/>
                                   
									</td>
				
				<?php }?>
			
			
			
			</tr> 

    
				<tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Note' ); ?>:
						</label>
					</td>
					<td >
						<?php
                        echo $editor->display('text_for_date','','100%','250','40','6');
						?>
					</td>
				</tr>
                
<tr>
<td class="key">
<label for="note">
<?php echo JText::_( 'Published' ); ?>:
</label>
</td>
<td>

<?php
echo $lists['published'];
?>

</td>
</tr>                
</table>   

</div>

<div class="span5">

<legend>
Repeat Event
</legend>
<table>
<tr>

<td valign="top" >
 <input type="radio" value="no_repeat"  name="repeat_method"   checked="checked" onchange="change_type('no_repeat')"  />Don't repeat this event<br/>
 <input type="radio" value="daily" name="repeat_method"    onchange="change_type('daily');"    />Repeat daily<br/>
 <input type="radio" value="weekly" name="repeat_method"   onchange="change_type('weekly');" />Repeat weekly<br/>
 <input type="radio" value="monthly" name="repeat_method"   onchange="change_type('monthly');"  />Repeat monthly<br/>
 <input type="radio" value="yearly" name="repeat_method"   onchange="change_type('yearly');"   />Repeat yearly<br/>
</td>
   
<td  style="padding-left:15px"  valign="top">
<div id="daily" style="display:none">

Repeat every <input type="text" id="repeat_input" size="5"   name="repeat"  onclick="return input_repeat()"  onkeypress="return checknumber(repeat_input)" value="1"   /> <span id="repeat"></span> <label id="year_month" style="display:none"><?php echo $lists['year_month'] ?></label>
</div><br />
  
 
<input type="hidden"   id="daily1" />
<input type="hidden" id="weekly1" />
<input type="hidden"  id="monthly1" />
<input type="hidden"  id="yearly1" />

<div class="key"  id="weekly" style="display:none">



 <input type="checkbox" value="Mon"  id="week_1"  onchange="week_value()"    />Mon
 <input  type="checkbox" value="Tue" id="week_2"   onchange="week_value()"    />Tue
 <input type="checkbox" value="Wed" id="week_3"  onchange="week_value()"  />Wed
 <input type="checkbox" value="Thu" id="week_4"  onchange="week_value()"   />Thu
 <input type="checkbox" value="Fri" id="week_5"   onchange="week_value()"   />Fri
 <input type="checkbox" value="Sat" id="week_6"   onchange="week_value()"   />Sat
 <input type="checkbox" value="Sun" id="week_7"   onchange="week_value()"   />Sun

<input type="hidden" name="week" id="week"  />



</div><br />



<div class="key" id="monthly" style="display:none">



 

<input type="radio" id="radio1"   onchange="radio_month()" name="month_type" value="1" checked="checked"  />on the: <input type="text" onkeypress="return checknumber(month)" name="month" size="3" id="month"  /><br/>
<input type="radio" id="radio2" onchange="radio_month()" name="month_type" value="2"   />on the:  <?php echo $lists['monthly_list'] ?> <?php echo $lists['month_week'] ?>
</div><br />
<script>
window.onload=radio_month();
</script>


</td>
</tr>

<tr id="repeat_until" style="display:none">
<td>
Repeat until: </td>
<td>
<?php $form->setValue('date_end',null,''); echo $form->getInput('date_end'); ?><img class="calendar" id="de_d_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px; margin-left:5px;" />
</td>
</tr>
</table>


</div>







     
		<input type="hidden" name="option" value="com_spidercalendar" />
		<input type="hidden" name="task" value="" />      
        <input type="hidden" name="calendar" value="<?php echo $lists['calendar']; ?>" />   
		
        
        </form>
        <?php
		
	}   
	
public static function showNote(&$rows, &$pageNav, &$lists,$option){

		JToolBarHelper::title( JText::_( 'Event Manager for calendar' ).' <font style="color:red">'.$lists['calendar_name'].'</font>', 'generic.png' );

		JHTML::_('behavior.tooltip');	
		JHTML::_('behavior.calendar');
		jimport('joomla.form.form');

		
		
		
$form = JForm::getInstance('naForm',dirname(__FILE__)."/datefields.xml");
//echo dirname(__FILE__).DS."default.xml";

	?>
<script type="text/javascript">
window.addEvent('domready', function() {Calendar.setup({
				inputField: "st_d",
				ifFormat: "%Y-%m-%d",
				button: "st_d_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});
window.addEvent('domready', function() {Calendar.setup({
				inputField: "en_d",
				ifFormat: "%Y-%m-%d",
				button: "en_d_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});
				
				
Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order_note.value     = order;
    form.filter_order_Dir_note.value = dir;
    submitform( task );
}				
				
				</script>
	<form action="index.php?option=com_spidercalendar" method="post" name="adminForm" id="adminForm">
      <a  style="float:right;color: red;font-size: 19px;text-decoration: none;" target="_blank" href="http://web-dorado.com/products/joomla-calendar.html" >
	<img style="float:right" src="components/com_spidercalendar/elements/header.png" /><br>
	<span style="padding-left:25px;">Get the full version  </span>
	</a>
	<p style="font-size:14px">
	<a href="http://web-dorado.com/spider-calendar-guide-step-3.html" target="_blank" style="color:blue;text-decoration:none">User Manual</a><br>
	This section allows you to create/edit the events of a particular calendar.<br>
	You can add unlimited number of events for each calendar.
	<a href="http://web-dorado.com/spider-calendar-guide-step-3.html" target="_blank" style="color:blue;text-decoration:none">More...</a>
</p>  
		
    
  <table width="100%">

        <tr>

            <td align="left" width="100%">
                <input type="text" name="search_note" id="search_note" value="<?php echo $lists['search_note'];?>" class="text_area" placeholder="Search" style="margin:0px" />

				From:<?php
$form->setValue('startdate',null,$lists['startdate']); echo $form->getInput('startdate'); ?><img class="calendar" id="st_d_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px;margin-left:5px" />
To:  <?php
$form->setValue('enddate',null,$lists['enddate']); echo $form->getInput('enddate'); ?><img class="calendar" id="en_d_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px;margin-left:5px" /> 

				
				
				
				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.getElementById('search_note').value='';document.getElementById('st_d').value='';document.getElementById('en_d').value='';this.form.submit();" data-original-title="Clear"><i class="icon-remove"></i></button>
				
				
				
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $pageNav->getLimitBox(); ?>
				</div>


            </td>

        </tr>

    </table>      
		
		
		
		
    <table class="table table-striped">
    <thead>
    	<tr>
        	<th width="20">
            <input type="checkbox" name="toggle"
 value="" onclick="Joomla.checkAll(this)">
            </th>
			 <th width="20" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></th>

			 <th width="250" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>

            <th width="80" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Date', 'date', @$lists['order_Dir'], @$lists['order'] ); ?></th>
           		 <th width="80" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Time', 'time', @$lists['order_Dir'], @$lists['order'] ); ?></th>
                <th width="80" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Category', 'cattitle', @$lists['order_Dir'], @$lists['order'] ); ?></th> 
                
				<th width="80" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'User', 'userID', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			<th width="20" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Published', 'published',@$lists['order_Dir'], @$lists['order'] ); ?>
			</th>
			
        </tr>
    </thead>
	<tfoot>
		<tr>
			<td colspan="11">
			 <?php 
			
			 echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
                
    <?php
	$db =JFactory::getDBO();
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
			$row = &$rows[$i];
			
			if($row->cattitle=='')
			$cattitle='Uncotegorized';
			else
			$cattitle=$row->cattitle;
			
			$userName='Administrator';
			if($row->userID)
			{
			$query = "SELECT * FROM #__users WHERE id=".$db->escape($row->userID) ."";
			$db->setQuery( $query);
			$user = $db->loadRow();
			
			$userName=$user[1];
			
			}
			
			$checked 	= JHTML::_('grid.id', $i, $row->id);
			$published 	= published($row, $i, 'event'); 
			// prepare link for id column
			$link 		= JRoute::_( 'index.php?option=com_spidercalendar&task=edit_event&cid[]='. $row->id );
			echo '<tr class="row'.$k.'">
        	<td>'.$checked.'</td>
			<td align="center"><a href="'.$link.'">'.$row->id.'</a></td>

			<td align="center"><a href="'.$link.'">'.$row->title.'</a></td>

			<td align="center">'.$row->date;
			
			if($row->date_end!="" and $row->date_end!='0000-00-00')
			echo "  -  ".$row->date_end."";
			
			echo '</td>
			<td align="center">'.$row->time.'</td>
			<td align="center">'.$cattitle.'</td>
			<td align="center">'.$userName.'</td>
        	<td align="center">'.$published.'</td>            
        </tr>';
$k = 1 - $k;
	}
	?>
    
    </table>
    <input type="hidden" name="option" value="com_spidercalendar">
    <input type="hidden" name="task" value="event">    
    <input type="hidden" name="boxchecked" value="0"> 
	<input type="hidden" name="filter_order_note" value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir_note" value="<?php echo $lists['order_Dir'] ?>" />  
    <input type="hidden" name="calendar" value="<?php echo $lists['calendar']; ?>" />  
    
     
    </form>
    <?php
	
}

//edit note
public static function editNote(&$lists, &$row, &$categories){

JToolBarHelper::title( JText::_( 'Edit an event for calendar' ).' <font style="color:red">'.$lists['calendar_name'].'</font>', 'generic.png' );
JHTML::_('behavior.tooltip');	
JHTML::_('behavior.calendar');
JRequest::setVar( 'hidemainmenu', 1 );
$editor	=JFactory::getEditor();
$user =JFactory::getUser();
	
jimport('joomla.form.form');

$form = JForm::getInstance('naForm',dirname(__FILE__)."/datefields.xml");

		?><script language="javascript" type="text/javascript">
window.addEvent('domready', function() {Calendar.setup({
				inputField: "date",
				ifFormat: "%Y-%m-%d",
				button: "date_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});
				window.addEvent('domready', function() {Calendar.setup({
				inputField: "de_d",
				ifFormat: "%Y-%m-%d",
				button: "de_d_img",
				align: "Tl",
				singleClick: true,
				firstDay: 0
				});});

        
<!--
Joomla.submitbutton= function (pressbutton)  {

var form = document.adminForm;

if (pressbutton == 'cancel_event') {

submitform( pressbutton );

return;

}

if(form.date.value.search(/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/))
{
	  alert('Invalid Date');
}
else
		if(form.date.value.search(/^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/))
		{
			  alert('Invalid Date');
		}
		else
		if(form.selhour_from.value=="" && form.selminute_from.value=="" && form.selhour_to.value=="" && form.selminute_to.value=="")
			submitform( pressbutton );
			else
			if(form.selhour_from.value!="" && form.selminute_from.value!="" && form.selhour_to.value=="" && form.selminute_to.value=="")
				submitform( pressbutton );
				else
				if(form.selhour_from.value!="" && form.selminute_from.value!="" && form.selhour_to.value!="" && form.selminute_to.value!="")
					submitform( pressbutton );
					else
					alert('Invalid Time');
		}
		
		function check12hour(id)
			{	
				if(typeof(event)!='undefined')
				{
					var e = event; // for trans-browser compatibility
					var charCode = e.which || e.keyCode;
						input=document.getElementById(id);
		
		 
						if(charCode==48 && input.value.length==0)
						return false;
						
						if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
		
						hour=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
						hour=parseFloat(hour);
						if(document.getSelection()!='')
						return true;
						
						if((hour<0) || (hour>12))
						return false;
				}
						return true;
			}
		
function checkhour(id)
	{	
		if(typeof(event)!='undefined')
		{
			var e = event; // for trans-browser compatibility
			var charCode = e.which || e.keyCode;

				if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;

				hour=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
				hour=parseFloat(hour);
				if(document.getSelection()!='')
							return true;
				if((hour<0) || (hour>23))
				return false;
		}
				return true;

	} 
function checkminute(id)
	{	
	if(typeof(event)!='undefined')
		{
		var e = event; // for trans-browser compatibility
		var charCode = e.which || e.keyCode;

		if (charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

			minute=""+document.getElementById(id).value+String.fromCharCode(e.charCode);
			minute=parseFloat(minute);
			if(document.getSelection()!='')
							return true;
			if((minute<0) || (minute>59))
		return false;
		}
				return true;
	}	
	
		function checknumber(id)
			{	
				if(typeof(event)!='undefined')
				{
					var e = event; // for trans-browser compatibility
					var charCode = e.which || e.keyCode;
		
						if (charCode > 31 && (charCode < 48 || charCode > 57))
						return false;
		
				}
						return true;
			}	
	
	
	
	
		
		function add_0(id)
		{
		
		    input=document.getElementById(id);
		
		    if(input.value.length==1)
		
		    {
		
			input.value='0'+input.value;
		
			input.setAttribute("value", input.value);
		
		    }
		
		}
		
function change_type(type)
{
	
	if(document.getElementById('daily1').value=='')
		document.getElementById('daily1').value=1;
	
	if(document.getElementById('weekly1').value=='')
		document.getElementById('weekly1').value=1;
	
	if(document.getElementById('monthly1').value=='')
		document.getElementById('monthly1').value=1;
	
	if(document.getElementById('yearly1').value=='')
		document.getElementById('yearly1').value=1;
	
	

	switch(type)
{
	
	
	case 'no_repeat':	
document.getElementById('daily').setAttribute('style','display:none');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').setAttribute('style','display:none');
//document.getElementById('repeat_input').value='';
document.getElementById('month').value='';
document.getElementById('de_d').value=''
break;

	case 'daily':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('repeat').innerHTML='Day(s)'
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('daily1')};
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_input').value=document.getElementById('daily1').value;


break;

case 'weekly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('weekly').removeAttribute('style');
document.getElementById('monthly').setAttribute('style','display:none');
document.getElementById('repeat').innerHTML='Week(s) on :'
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('weekly1')};
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').value=document.getElementById('weekly1').value;
break;

case 'monthly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').removeAttribute('style');
document.getElementById('repeat').innerHTML='Month(s)'
document.getElementById('repeat_input').value=document.getElementById('monthly1').value;
document.getElementById('month').value='';
document.getElementById('year_month').setAttribute('style','display:none');
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('monthly1')};
break;

case 'yearly':	
document.getElementById('daily').removeAttribute('style');
document.getElementById('year_month').removeAttribute('style');
document.getElementById('weekly').setAttribute('style','display:none');
document.getElementById('monthly').removeAttribute('style');
document.getElementById('repeat').innerHTML='Year(s) in '
document.getElementById('repeat_input').value=document.getElementById('yearly1').value;
document.getElementById('month').value='';
document.getElementById('repeat_until').removeAttribute('style');
document.getElementById('repeat_input').onchange=function onchange(event) {return input_value('yearly1')};
break;

	
	
}
		
}

function week_value()
{
var value='';
for(i=1; i<=7; i++)
{

if (document.getElementById('week_'+i).checked)
{
	value=value+document.getElementById('week_'+i).value+',';
	
}
	
}
document.getElementById('week').value=value;



}


		
function radio_month()
{
	if(document.getElementById('radio1').checked==true)
		{	
		document.getElementById('monthly_list').disabled=true;
		document.getElementById('month_week').disabled=true;
		document.getElementById('month').disabled=false;
		}
	else
	{
	document.getElementById('month').disabled=true;
	document.getElementById('monthly_list').disabled=false;
		document.getElementById('month_week').disabled=false;
	}
	

}

function input_value(id)
	
{
	document.getElementById(id).value=document.getElementById('repeat_input').value;
}

</script> 
<?php 
		$calendar =JTable::getInstance('spidercalendar_calendar', 'Table');
		// load the row from the db table
		$calendar->load( $lists['calendar']);
		
		
		?>    
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" id="selday" name="selday" value="<?php echo substr( $row ->date,8,2); ?>"/>
        <input type="hidden" id="selmonth" name="selmonth" value="<?php echo substr( $row ->date,5,2)?>" />
        <input type="hidden" id="selyear" name="selyear" value="<?php echo substr($row -> date,0,4)?>" />

	
<div class="span5">


<legend>
Event Details
</legend>
<table class="admintable">
                <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Title' ); ?>:
						</label>
					</td>
                	<td>
                    	<input type="text" id="title" name="title" size="41"  value="<?php echo htmlspecialchars($row->title, ENT_QUOTES); ?>" />
                    </td>
				</tr>    
				<tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Select Category' ); ?>:
						</label>
					</td>
					
                	<td>
					
                    	<select id="category" name="category" style="width:233px" >
						<option>--Select Category--</option>
						<?php 
						foreach($categories as $category)
						{?>
						<option value="<?php echo $category->id?>" <?php if ($category->id==$row->category) echo 'selected="selected"';?>><?php echo $category->title?></option>
						
						
					<?php	}
						?>
						
						</select>
                    </td>
				</tr> 			
                <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Date' ); ?>:
						</label>
					</td>
				 
                <td>
                    	<?php
$form->setValue('date',null,$row->date); echo $form->getInput('date'); ?><img class="calendar" id="date_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px;margin-left:5px;" />
                </td>           
                </tr> 
  <tr>
					<td class="key">
						<label for="message">
							<?php echo JText::_( 'Time' ); ?>:
						</label>
					</td>
                                                 <td>
                                                                  
                                     <?php 
				     if(!$row ->time)
				     { 
				     	$from[0]="";
				     	$from[1]="";
				     	$to[0]="";
				     	$to[1]="";
				     }
				     else
				     {
					     $from_to = explode("-", $row ->time);
					     $from    = explode(":", $from_to[0]);
						if(isset($from_to[1]))
					     		$to = explode(":", $from_to[1]);
						else
						{
							$to[0]="";
							$to[1]="";
						}
				     }
				     ?> 
                                      <?php if($calendar->time_format==0){?>  
                                    <input type="text" id="selhour_from" name="selhour_from" size="1" style="text-align:right;width:30px" onkeypress="return checkhour('selhour_from')" value="<?php echo $from[0]; ?>" title="from"  onblur="add_0('selhour_from')"/> <b>:</b>
                                    <input type="text" id="selminute_from" name="selminute_from" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_from')" value="<?php echo substr($from[1],0,2); ?>"  title="from" onblur="add_0('selminute_from')"/> <span style="font-size:12px">&nbsp;-&nbsp;</span>
                                    <input type="text" id="selhour_to" name="selhour_to" size="1" style="text-align:right;width:30px" onkeypress="return checkhour('selhour_to')" value="<?php echo $to[0]; ?>"  title="to" onblur="add_0('selhour_to')" /> <b>:</b>
                                    <input type="text" id="selminute_to" name="selminute_to" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_to')" value="<?php echo substr($to[1],0,2); ?>"  title="to" onblur="add_0('selminute_to')"/>
                                    
									<?php }   if($calendar->time_format==1){?>
									 
									<input type="text" id="selhour_from" name="selhour_from" size="1" style="text-align:right;width:30px" onkeypress="return check12hour('selhour_from')" value="<?php echo $from[0]; ?>" title="from"  onblur="add_0('selhour_from')"/> <b>:</b>
                                    <input type="text" id="selminute_from" name="selminute_from" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_from')" value="<?php echo substr($from[1],0,2); ?>"  title="from" onblur="add_0('selminute_from')"/> 
                                    <select id="select_from" style="width:60px" name="select_from" >
									<option <?php if(substr($from[1],3,2)=="AM") echo 'selected="selected"'; ?>>AM</option>
									<option <?php if(substr($from[1],3,2)=="PM") echo 'selected="selected"'; ?>>PM</option>
									
									</select>
								   
									<span style="font-size:12px">&nbsp;-&nbsp;</span>
									
									<input type="text" id="selhour_to" name="selhour_to" size="1" style="text-align:right;width:30px" onkeypress="return check12hour('selhour_to')" value="<?php echo $to[0]; ?>"  title="to" onblur="add_0('selhour_to')" /> <b>:</b>
                                    <input type="text" id="selminute_to" name="selminute_to" size="1" style="text-align:right;width:30px" onkeypress="return checkminute('selminute_to')" value="<?php echo substr($to[1],0,2); ?>"  title="to" onblur="add_0('selminute_to')"/>
                                     
									 <select id="select_to" style="width:60px" name="select_to">
									<option <?php if(substr($to[1],3,2)=="AM") echo 'selected="selected"'; ?>>AM</option>
									<option <?php if(substr($to[1],3,2)=="PM") echo 'selected="selected"';  ?>>PM</option>
									
									</select>
									
									
									
									<?php }?>

								   </td>
				</tr> 
               
<tr>
<td class="key">
<label for="note">
<?php echo JText::_( 'Note' ); ?>:
</label>
</td>
<td >
<?php
echo $editor->display('text_for_date',
$row->text_for_date,'100%','250','40','6');
?>
</td>
</tr>
<tr>




<td class="key">
<label for="note">
<?php echo JText::_( 'Published' ); ?>:
</label>
</td>
<td >
<?php
echo $lists['published'];
?>
</td>
</tr>                
</table>   
     
</div>



<div class="span5">

<legend>
Repeat Event
</legend>
<table>
<tr>

<td valign="top" >
 <input type="radio" value="no_repeat"  name="repeat_method" <?php if ($row->repeat_method == 'no_repeat') echo 'checked="checked"' ?> checked="checked" onchange="change_type('no_repeat')"  />Don't repeat this event<br/>
 <input type="radio" value="daily" name="repeat_method" <?php if ($row->repeat_method == 'daily') echo 'checked="checked"' ?>  onchange="change_type('daily')"    />Repeat daily<br/>
 <input type="radio" value="weekly" name="repeat_method" <?php if ($row->repeat_method == 'weekly') echo 'checked="checked"' ?> onchange="change_type('weekly')" />Repeat weekly<br/>
 <input type="radio" value="monthly" name="repeat_method" <?php if ($row->repeat_method == 'monthly') echo 'checked="checked"'?> onchange="change_type('monthly')"  />Repeat monthly<br/>
 <input type="radio" value="yearly" name="repeat_method" <?php if ($row->repeat_method == 'yearly') echo 'checked="checked"' ?> onchange="change_type('yearly')"   />Repeat yearly<br/>
</td>
   
<td style="padding-left:10px" valign="top">
<div id="daily" style="display:<?php if ($row->repeat_method=='no_repeat') echo 'none';  ?>">

Repeat every <input type="text"  id="repeat_input" size="5" name="repeat" onkeypress="return checknumber(repeat_input)" value="<?php echo $row->repeat ?>"  /> 
<label id="repeat"><?php if($row->repeat_method=='daily') echo 'Day(s)';  
if($row->repeat_method=='weekly') echo 'Week(s) on :';
if($row->repeat_method=='monthly') echo 'Month(s)';
if($row->repeat_method=='yearly') echo 'Year(s) in';
?></label> <label id="year_month" style="display:<?php if($row->repeat_method!='yearly') echo 'none'; ?>"><?php echo $lists['year_month'] ?></label>
<input  type="hidden" value="<?php if($row->repeat_method=='daily') echo $row->repeat ?>"    id="daily1" />
<input type="hidden" value="<?php if($row->repeat_method=='weekly') echo $row->repeat ?>"  id="weekly1" />
<input type="hidden"  value="<?php if($row->repeat_method=='monthly') echo $row->repeat ?>"  id="monthly1" />
<input type="hidden" value="<?php if($row->repeat_method=='yearly') echo $row->repeat ?>"   id="yearly1" />

</div><br />
  



<div class="key"  id="weekly" style="display:<?php if ($row->repeat_method!='weekly') echo 'none';  ?>">



 <input type="checkbox" value="Mon"  id="week_1" onchange="week_value()" <?php if (in_array('Mon',explode(',',$row->week))) echo 'checked="checked"' ?>   />Mon
 <input  type="checkbox" value="Tue" id="week_2"  onchange="week_value()" <?php if (in_array('Tue',explode(',',$row->week))) echo 'checked="checked"' ?>   />Tue
 <input type="checkbox" value="Wed" id="week_3" onchange="week_value()" <?php if (in_array('Wed',explode(',',$row->week))) echo 'checked="checked"' ?> />Wed
 <input type="checkbox" value="Thu" id="week_4" onchange="week_value()" <?php if (in_array('Thu',explode(',',$row->week))) echo 'checked="checked"' ?>  />Thu
 <input type="checkbox" value="Fri" id="week_5"  onchange="week_value()"  <?php if (in_array('Fri',explode(',',$row->week))) echo 'checked="checked"' ?> />Fri
 <input type="checkbox" value="Sat" id="week_6"  onchange="week_value()" <?php if (in_array('Sat',explode(',',$row->week))) echo 'checked="checked"' ?>  />Sat
 <input type="checkbox" value="Sun" id="week_7"  onchange="week_value()"  <?php if (in_array('Sun',explode(',',$row->week))) echo 'checked="checked"' ?> />Sun

<input type="hidden" name="week" id="week" value="<?php echo $row->week ?>" />



</div><br />



<div class="key" id="monthly" style="display:<?php if ($row->repeat_method!='monthly' && $row->repeat_method!='yearly') echo 'none';  ?>">
<input type="radio" id="radio1" name="month_type" onchange="radio_month()" value="1" checked="checked" <?php if ($row->month_type == 1) echo 'checked="checked"' ?>  />on the: <input type="text" name="month" size="3" onkeypress="return checknumber(month)"  id="month" value="<?php echo $row->month ?>" /><br/>
<input type="radio" id="radio2" name="month_type" onchange="radio_month()" value="2" <?php if ($row->month_type == 2) echo 'checked="checked"' ?> />on the: <?php echo $lists['monthly_list'] ?> <?php echo $lists['month_week'] ?>



</div>  <br />
<script>
window.onload=radio_month();


</script>


</td>
</tr>

<tr id="repeat_until" style="display:<?php if($row->repeat_method=='no_repeat') echo 'none'; ?>">
<td>
Repeat until: </td>
<td>
<?php if ($row ->date_end=='0000-00-00')
$row ->date_end="";
$form->setValue('date_end',null,$row->date_end); echo $form->getInput('date_end'); ?><img class="calendar" id="de_d_img" src="templates/hathor/images/system/calendar.png" alt="calendario" style="position:relative; top:-5px;margin-left:5px" />
</td>
</tr>
</table>


</div>










<input type="hidden" name="option" value="com_spidercalendar" />
<input type="hidden" name="id" value="<?php echo $row->id?>" />        
<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
<input type="hidden" name="task" value="event" />   
<input type="hidden" name="calendar" value="<?php echo $lists['calendar']; ?>" />  

</form>
        <?php		
       }	
public static function onlycalendar()
{?>
  <div id="calendar"><?php include_once('components/com_spidercalendar/spidercalendar.php');?></div>
<?php
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// C A L E N D A R ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

public static function show_calendar(&$rows, &$pageNav, &$lists){

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

			
		?>
	<script type="text/javascript">
	
	 Joomla.submitbutton=function(pressbutton) 
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel_calendar') 
		{
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
	
Joomla.tableOrdering= function ( order, dir, task )  {
    var form = document.adminForm;
    form.filter_order_calendar.value     = order;
    form.filter_order_Dir_calendar.value = dir;
    submitform( task );
}
	</script>
	<form action="index.php?option=com_spidercalendar" method="post" name="adminForm" id="adminForm">
				<a  style="float:right;color: red;font-size: 19px;text-decoration: none;" target="_blank" href="http://web-dorado.com/products/joomla-calendar.html" >
	<img style="float:right" src="components/com_spidercalendar/elements/header.png" /><br>
	<span style="padding-left:25px;">Get the full version  </span>
	</a>
	<p style="font-size:14px">
	<a href="http://web-dorado.com/spider-calendar-guide-step-2.html" target="_blank" style="color:blue;text-decoration:none">User Manual</a><br>
	This section allows you to create calendars. You can add unlimited number of calendars.<a href="http://web-dorado.com/spider-calendar-guide-step-2.html" target="_blank" style="color:blue;text-decoration:none">More...</a>
</p>	
			<table width="100%">
		<tr>
			<td align="left" width="100%">
				<input type="text" name="search_calendar" id="search_calendar" value="<?php echo $lists['search_calendar'];?>" class="text_area"  placeholder="Search" style="margin:0px" />
				<button class="btn tip hasTooltip" type="submit" data-original-title="Search"><i class="icon-search"></i></button>
				<button class="btn tip hasTooltip" type="button" onclick="document.id('search_calendar').value='';this.form.submit();" data-original-title="Clear">
				<i class="icon-remove"></i></button>
				
				<div class="btn-group pull-right hidden-phone">
					<label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC');?></label>
					<?php echo $pageNav->getLimitBox(); ?>
				</div>

			</td>
		</tr>
		</table>   
		
			
		<table class="table table-striped" >
		<thead>
			<tr>            
				<th width="30" class="title"><?php echo "#" ?></td>
				<th width="20"><input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)"></th>
				<th width="30" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></td>
				<th><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
				<th width="30" class="title">Manage Events</td>
                
				<th nowrap="nowrap" width="100"><?php echo JHTML::_('grid.sort',   'Published', 'published',@$lists['order_Dir'], @$lists['order'] ); ?></th>
		  </tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="11">
				 <?php echo $pageNav->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
					
		<?php
		$k = 0;
		for($i=0, $n=count($rows); $i < $n ; $i++)
		{
			$row = &$rows[$i];
			$checked 	= JHTML::_('grid.id', $i, $row->id);
			$published 	= published($row, $i, 'calendar');;  
			$link 		= JRoute::_( 'index.php?option=com_spidercalendar&task=edit_calendar&cid[]='. $row->id );
			$link2 		= JRoute::_( 'index.php?option=com_spidercalendar&task=event&calendar='. $row->id );
	?>
			<tr class="<?php echo "row$k"; ?>">
				<td align="center"><?php echo $i+1?></td>
				<td><?php echo $checked?></td>
				<td align="center"><?php echo $row->id?></td>
				<td><a href="<?php echo $link;?>"><?php echo $row->title?></a></td>    
				<td align="center"><a href="<?php echo $link2;?>">Manage events</a></td>
				<td align="center"><?php echo $published?></td>
			</tr>
			<?php
			$k = 1 - $k;
		}
		?>
		</table>
		<input type="hidden" name="option" value="com_spidercalendar">
		<input type="hidden" name="task" value="calendar">    
		<input type="hidden" name="boxchecked" value="0"> 
		<input type="hidden" name="filter_order_calendar" value="<?php echo $lists['order']; ?>" />
		<input type="hidden" name="filter_order_Dir_calendar" value="<?php echo $lists['order_Dir']; ?>" />       
		</form>
		<?php
	}


public static function edit_calendar(&$lists, &$row, &$themes, &$userGroups){
		
		JRequest::setVar( 'hidemainmenu', 1 );
		$editor	=JFactory::getEditor();
				?>
				
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel_calendar') {
		submitform( pressbutton );
		return;
		}
					
					
		submitform( pressbutton );
		
		}
		//-->
		function acces_level(length)
			{
				var value='';
				for(i=0; i<parseInt(length); i++)
					{

					if (document.getElementById('user_'+i).checked)
						{
							value=value+document.getElementById('user_'+i).value+',';
							
						}
						
					}
				document.getElementById('user').value=value;



			}
			
			
        </script>    

		<?php  //$editor->display('text_for_date','','100%','250','40','6');?>
		<form action="index.php?option=com_spidercalendar&task=calendar" method="post" name="adminForm" id="adminForm">
		<div class="width-45 fltlft ">
		<fieldset class="adminform" >
		<legend>Calendar title</legend>
		<table class="admintable">
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Title' ); ?>:
								</label>
							</td>
							<td >
											<input type="text" name="title" id="title" size="30" value="<?php echo htmlspecialchars($row->title) ?>" />
							</td>
						</tr>
					
							
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Default Year' ); ?>:
								</label>
							</td>
							<td >
											<input type="text" name="def_year" id="def_year" size="30" value="<?php echo $row->def_year ?>" />
							</td>
						</tr>
						
						
							<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Default Month' ); ?>:
								</label>
							</td>
							<td >
							<?php echo $lists['def_month'] ?>
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Published' ); ?>:
								</label>
							</td>
							<td id="publish">
								<?php
								echo $lists['published'];
								?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Use 12-hour time format' ); ?>:
								</label>
							</td>
							<td >
											<?php echo $lists['time_format'] ?>
							</td>
						</tr>
						
		 </table> 
		</fieldset>
		</div>
		<div class="width-45 fltlft" >
		<fieldset class="adminform">
		<legend>Front end event management access level</legend>
		<table>
				<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Allow User to add and manage events' ); ?>:
								</label>
							</td>
							<td >
								<?php $checked_UserGroup=explode(',',$row->gid);
							
								for($i=0;$i<count($userGroups);$i++)
								{
								
								echo '<input type="checkbox" value="'.$userGroups[$i]->id .'"  id="user_'.$i.'"'; 
								
								if(in_array($userGroups[$i]->id ,$checked_UserGroup))
								echo'checked="checked"';
								
								echo 'onchange="acces_level('.count($userGroups).')"/>'.$userGroups[$i]->title.'<br>' ; 
							 
								
								} 
								?>
								<input type="hidden" name="gid" value="<?php echo $row->gid ?>" id="user" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Allow User to publish/unpublish events' ); ?>:
								</label>
							</td>
							<td >
							<?php echo $lists['allow_publish'] ;?>
							
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Will admin get an email about new event' ); ?>:
								</label>
							</td>
							<td >
							<?php echo $lists['get_email'] ;?>
							
							</td>
						</tr>
						<tr  id="email">
						
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Email' ); ?>:
								</label>
							</td>
							<td >
							<input type = "text" value="<?php echo $row->email?>" name="email" size="50" />
						
							</td>
						
						</tr>

</table>
</fieldset>
</div>

		 
		<input type="hidden" name="option" value="com_spidercalendar" />        
		<input type="hidden" name="id" value="<?php echo $row->id?>" />        
		<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
		<input type="hidden" name="task" value="" />        
		</form>
				<?php		
			   
		}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////// T H E M E ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		public static function show_theme(&$rows, &$pageNav, &$lists){
			JHTML::_('behavior.tooltip');	
			
		?>
	<script type="text/javascript">
	
	function submitbutton(pressbutton) 
	{
		var form = document.adminForm;
		if (pressbutton == 'cancel_calendar') 
		{
			submitform( pressbutton );
			return;
		}
		submitform( pressbutton );
	}
	
Joomla.tableOrdering= function ( order, dir, task ) {
		var form = document.adminForm;
		form.filter_order_theme.value     = order;
		form.filter_order_Dir_theme.value = dir;
		submitform( task );
	}
	</script>
	<form action="index.php?option=com_spidercalendar&task=theme" method="post" name="adminForm" id="adminForm">
<a  style="float:right;color: red;font-size: 19px;text-decoration: none;" target="_blank" href="http://web-dorado.com/products/joomla-calendar.html" >
	<img style="float:right" src="components/com_spidercalendar/elements/header.png" /><br>
	<span style="padding-left:25px;">Get the full version  </span>
	</a>
	<p style="font-size:14px">
	<a href="http://web-dorado.com/spider-calendar-guide-step-6/spider-calendar-guide-step-6-1.html" target="_blank" style="color:blue;text-decoration:none">User Manual</a><br>
	This section allows you to create/edit themes for the calendars.
This feature is disabled for the non-commercial version.<a href="http://web-dorado.com/spider-calendar-guide-step-6/spider-calendar-guide-step-6-1.html" target="_blank" style="color:blue;text-decoration:none">More...</a><br>
Here are some examples of 11 standard templates included in the commercial version.
<a href="http://demo.web-dorado.com/spider-calendar.html" target="_blank" style="color:blue;text-decoration:none">Demo</a>

<p>

		<img src="components/com_spidercalendar/elements/theme.jpg" />
    
<form>
	<?php
	}
		
		
		
		
		
		public static function edit_theme(&$row, &$lists){
		
		JRequest::setVar( 'hidemainmenu', 1 );
		$editor	=JFactory::getEditor();
		$document		=JFactory::getDocument();
		$document->addScript(JURI::root() . 'administrator/components/com_spidercalendar/jscolor/jscolor.js');
		$document->addScript(JURI::root() . 'administrator/components/com_spidercalendar/elements/theme_reset.js');
				?>
				
		<script language="javascript" type="text/javascript">
		<!--
		function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel_theme') {
		submitform( pressbutton );
		return;
		}
		
		if(document.getElementById('title').value==''){
		alert('The theme must have a title')
		return;			
		}
		submitform( pressbutton );
		}
		//-->
	
		function change_width(){
		width=parseInt(document.getElementById('width').value)+45+parseInt(document.getElementById('border_width').value);
		alert(width)
		
		height=550;
	
		document.getElementsByClassName('modal hide')[0].style.width=width;
		
		
		
		
		}
		
		function set_preview()
{
	appWidth			=document.getElementById('width').style.width;
	appHeight			=550;
	
 
document.getElementById('modal-preview').style.width=appWidth+"px";
document.getElementById('modal-preview').style.height=appHeight+"px";

}	
		
		
        </script> 

		
            
		<?php $theme_ids=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17);?>
		
<style>
#modal-preview
{
width:750px;
}
.modal-body
{
height:550px;
}

iframe
{
width:100%;
height:100%;
}
</style>			
		
		
		<form action="index.php"  method="post" name="adminForm" id="adminForm">
	
	<ul class="nav nav-tabs">
			<li class="active"><a href="#general_parameters" data-toggle="tab">General Parameters</a></li>
			<li><a href="#header_parameters" data-toggle="tab">Header Parameters</a></li>
			<li><a href="#body_parameters" data-toggle="tab">Body Parameters</a></li>
			<li><a href="#popup_parameters" data-toggle="tab">Popup window Parameters</a></li>
			<li><a href="#other_parameters" data-toggle="tab">Other Views Parameters</a></li>
			
		</ul>
<div class="tab-content">	
		<div class="tab-pane active" id="general_parameters">
		
						<fieldset class="adminform">
						<legend>General Parameters</legend>
				<table  class="admintable">
		
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Title' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="title" id="title" size="20" value="<?php echo htmlspecialchars($row->title) ?>" />
							</td>
						</tr>					                      
						<?php if(!in_array($row->id,$theme_ids)) { ?>
						<tr>
							<td class="key">
								<label for="name">
									<?php echo JText::_( 'Default theme' ); ?>:
								</label>
							</td>
							<td >
								<select id="slect_theme" onchange="set_theme()" >
								<option value="0"> Custom </option>
								<option value="1"> Theme1 </option>
								<option value="2"> Theme2 </option>
								<option value="3"> Theme3 </option>
								<option value="4"> Theme4 </option>
								<option value="5"> Theme5 </option>
								<option value="6"> Theme6 </option>
								<option value="7"> Theme7 </option>
								<option value="8"> Theme8 </option>
								<option value="9"> Theme9 </option>
								<option value="10"> Theme10 </option>
								<option value="11"> Theme11 </option>
								<option value="12"> Shiny Red </option>
								<option value="13"> Shiny Blue </option>
								<option value="14"> Shiny Green </option>
								<option value="15"> Shiny Orange </option>
								<option value="16"> Shiny Pink </option>
								<option value="17"> Shiny Purple </option>
								</select>
							</td>
						</tr>	
						
						<?php } ?>						
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Width' ); ?>:
								</label>
							</td>
							<td >
								<input onchange="set_preview()" type="text" name="width" id="width" size="10" value="<?php echo $row->width ?>"  />
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'The first day of the week' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['week_start_day'] ?>
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Main border color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="border_color" id="border_color" size="20" value="<?php echo $row->border_color ?>" class="color" />
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Main border radius' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="border_radius" id="border_radius" size="10" value="<?php echo $row->border_radius ?>"  />
							</td>
						</tr> 
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Main border width' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" onchange="set_preview()" name="border_width" id="border_width" size="5" value="<?php echo $row->border_width ?>" />
							</td>
						</tr> 
						<tfoot>
						<tr style="text-align:center">
							<td colspan="11">
							<?php if(in_array($row->id,$theme_ids)){ ?>
		
		<img onclick="reset_theme_<?php echo $row->id ?>();" src="components/com_spidercalendar/elements/reset_theme.png" />
		<?php }?>
							</td>
						</tr>
					</tfoot>
						
						
						
						
						</table>
						</fieldset>
						</div>
						
						<div class="tab-pane" id="header_parameters">
						<fieldset class="adminform">
						<legend>Header Parameters</legend>
						<table class="admintable">
						
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Header height' ); ?>:
								</label>
							</td>
							<td >
								<input onchange="set_preview()" type="text" name="top_height" id="top_height" size="5" value="<?php echo $row->top_height ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Header background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="bg_top" id="bg_top" size="20" value="<?php echo $row->bg_top ?>" class="color" />
							</td>
						</tr> 
						
						
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Current month font size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="month_font_size" id="month_font_size" size="10" value="<?php echo $row->month_font_size ?>"  />
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Current Month color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_month" id="text_color_month" size="20" value="<?php echo $row->text_color_month ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Current Month arrow color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="arrow_color_month" id="arrow_color_month" size="20" value="<?php echo $row->arrow_color_month ?>" class="color" />
							</td>
						</tr>
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Arrow size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="arrow_size" id="arrow_size" size="10" value="<?php echo $row->arrow_size ?>"  />
							</td>
						</tr> 

						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Days of the week names color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_week_days" id="text_color_week_days" size="20" value="<?php echo $row->text_color_week_days ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Days of the week names cell height' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="week_days_cell_height" id="week_days_cell_height" size="10" value="<?php echo $row->week_days_cell_height ?>"  />
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Days of the week names background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="weekdays_bg_color" id="weekdays_bg_color" size="20" value="<?php echo $row->weekdays_bg_color ?>" class="color" />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Sunday background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="weekday_sunday_bg_color" id="weekday_sunday_bg_color" size="20" value="<?php echo $row->weekday_sunday_bg_color ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Days of the week names font size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="weekdays_font_size" id="weekdays_font_size" size="5" value="<?php echo $row->weekdays_font_size ?>"  />
							</td>
						</tr>
						
						
						</table>
						</fieldset>
						</div>
						
						</table>
						
						
						
						
						
							
						<div class="tab-pane" id="body_parameters">
						<fieldset class="adminform">
						<legend>Body Parameters</legend>
						<table class="admintable">
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="bg_bottom" id="bg_bottom" size="20" value="<?php echo $row->bg_bottom ?>" class="color" />
							</td>
						</tr> 		
										
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Cell height' ); ?>:
								</label>
							</td>
							<td >
								<input onchange="set_preview()" type="text" name="cell_height" id="cell_height" size="10" value="<?php echo $row->cell_height ?>"  />
							</td>
						</tr> 
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Text Color of Other Month\'s Days' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_other_months" id="text_color_other_months" size="20" value="<?php echo $row->text_color_other_months ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Background Color of Other Month\'s Days' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="bg_color_other_months" id="bg_color_other_months" size="20" value="<?php echo $row->bg_color_other_months ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Cell text color without events' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_this_month_unevented" id="text_color_this_month_unevented" size="20" value="<?php echo $row->text_color_this_month_unevented ?>" class="color" />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Cell text color with events' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_this_month_evented" id="text_color_this_month_evented" size="20" value="<?php echo $row->text_color_this_month_evented ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Cell background color with events' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="bg_color_this_month_evented" id="bg_color_this_month_evented" size="20" value="<?php echo $row->bg_color_this_month_evented ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event title color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_title_color" id="event_title_color" size="20" value="<?php echo $row->event_title_color ?>" class="color" />
							</td>
						</tr>
						
						<td class="key">
								<label for="published">
									<?php echo JText::_( 'Current day cell border color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="current_day_border_color" id="current_day_border_color" size="20" value="<?php echo $row->current_day_border_color ?>" class="color" />
							</td>
						</tr>
						
						<td class="key">
								<label for="published">
									<?php echo JText::_( 'Cell border color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="cell_border_color" id="cell_border_color" size="20" value="<?php echo $row->cell_border_color ?>" class="color" />
							</td>
						</tr>
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Sundays text color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="text_color_sun_days" id="text_color_sun_days" size="20" value="<?php echo $row->text_color_sun_days ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Sundays cell background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="sundays_bg_color" id="sundays_bg_color" size="20" value="<?php echo $row->sundays_bg_color ?>" class="color"  />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Sundays font size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="sundays_font_size" id="sundays_font_size" size="5" value="<?php echo $row->sundays_font_size ?>"  />
							</td>
						</tr> 
						
						
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Days font size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="other_days_font_size" id="other_days_font_size" size="5" value="<?php echo $row->other_days_font_size ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Show time in cell' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['show_time'] ?>
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Number of displayed events' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="number_of_shown_evetns" id="number_of_shown_evetns" size="5" value="<?php echo $row->number_of_shown_evetns ?>"  />
							</td>
						</tr> 
												<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Titles Background Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="ev_title_bg_color" id="ev_title_bg_color" size="20" value="<?php echo $row->ev_title_bg_color ?>" class='color'  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Views Tabs Background Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="views_tabs_bg_color" id="views_tabs_bg_color" size="20" value="<?php echo $row->views_tabs_bg_color ?>" class='color'  />
							</td>
						</tr>
					
							<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Views Tabs Text Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="views_tabs_text_color" id="views_tabs_text_color" size="20" value="<?php echo $row->views_tabs_text_color ?>" class='color'  />
							</td>
						</tr>
									

							<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Views Tabs Font Size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="views_tabs_font_size" id="views_tabs_font_size" size="5" value="<?php echo $row->views_tabs_font_size ?>"  />
							</td>
						</tr>
						
						
						</table>
						</fieldset>
						</div>
						
						
						
						
						
						
						
					
						
						
						
						
						
						<div class="tab-pane" id="popup_parameters">
						<fieldset class="adminform">
						<legend>Pop-up window Parameters</legend>
						<table class="admintable">
						
						<tr>
							<td class="adminformlist">
								<label for="published">
									<?php echo JText::_( 'Date format in pop-up (w/d/m/y)' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_format" id="date_format" size="5" value="<?php if($row->date_format) echo $row->date_format; else echo 'w/d/m/y'; ?>"  />
							</td>
						</tr>
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event title color in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="title_color" id="title_color" size="20" value="<?php echo $row->title_color ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event title font size in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="title_font_size" id="title_font_size" size="5" value="<?php echo $row->title_font_size ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event title font family in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['title_font'] ?>
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event title font style in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['title_style'] ?>
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date color in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_color" id="date_color" size="20" value="<?php echo $row->date_color ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date font size in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_size" id="date_size" size="5" value="<?php echo $row->date_size ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date font family in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['date_font'] ?>
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date style in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['date_style'] ?>
							</td>
						</tr>
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Arrow background color in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="next_prev_event_bgcolor" id="next_prev_event_bgcolor" size="20" value="<?php echo $row->next_prev_event_bgcolor ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Arrow color in pop-up' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="next_prev_event_arrowcolor" id="next_prev_event_arrowcolor" size="20" value="<?php echo $row->next_prev_event_arrowcolor ?>" class="color" />
							</td>
						</tr>
		
		
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Pop-up background color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="show_event_bgcolor" id="show_event_bgcolor" size="20" value="<?php echo $row->show_event_bgcolor ?>" class="color" />
							</td>
						</tr>

						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Pop-up width' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="popup_width" id="popup_width" size="10" value="<?php echo $row->popup_width ?>"  />
							</td>
						</tr> 
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Pop-up height' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="popup_height" id="popup_height" size="10" value="<?php echo $row->popup_height ?>"  />
							</td>
						</tr> 
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Show the repeat rate' ); ?>:
								</label>
							</td>
							<td >
								<?php echo $lists['show_repeat'] ?>
							</td>
						</tr>
						
						</table>
						</fieldset>
						</div>
						
						
						
						
						
						<div class="tab-pane" id="other_parameters">
						<fieldset class="adminform">
						<legend>Other Views Parameters</legend>
						<table class="admintable">
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date Background Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_bg_color" id="date_bg_color" size="20" value="<?php echo $row->date_bg_color ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Background Color 1' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_bg_color1" id="event_bg_color1" size="20" value="<?php echo $row->event_bg_color1 ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Background Color 2' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_bg_color2" id="event_bg_color2" size="20" value="<?php echo $row->event_bg_color2 ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Number Background Color 1' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_num_bg_color1" id="event_num_bg_color1" size="20" value="<?php echo $row->event_num_bg_color1 ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Number Background Color 2' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_num_bg_color2" id="event_num_bg_color2" size="20" value="<?php echo $row->event_num_bg_color2 ?>" class="color" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Number Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_num_color" id="event_num_color" size="20" value="<?php echo $row->event_num_color ?>" class="color" />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Day And Moth Font Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="day_month_font_color" id="day_month_font_color" size="20" value="<?php echo $row->day_month_font_color ?>" class="color" />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Day of the Week Font Color' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="week_font_color" id="week_font_color" size="20" value="<?php echo $row->week_font_color ?>" class="color" />
							</td>
						</tr>
						
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date Font Size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_font_size" id="date_font_size" size="5" value="<?php echo $row->date_font_size ?>" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Number Font Size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_num_font_size" id="event_num_font_size" size="5" value="<?php echo $row->event_num_font_size ?>"  />
							</td>
						</tr>
						
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Event Cell Height' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="event_table_height" id="event_table_height" size="5" value="<?php echo $row->event_table_height ?>" />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Date Cell Height' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="date_height" id="date_height" size="5" value="<?php echo $row->date_height ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Day And Month Font Size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="day_month_font_size" id="day_month_font_size" size="5" value="<?php echo $row->day_month_font_size ?>"  />
							</td>
						</tr>
						
						<tr>
							<td class="key">
								<label for="published">
									<?php echo JText::_( 'Day of the Week Font Size' ); ?>:
								</label>
							</td>
							<td >
								<input type="text" name="week_font_size" id="week_font_size" size="5" value="<?php echo $row->week_font_size ?>"  />
							</td>
						</tr>
						
						
						
						</table>
						</fieldset>
						</div>
						
						
						
						
						
						
						</div>
						
						
						
						
						
						<script>
					
							window.onload= set_preview();
						</script>
					
						



		</table>       
		<input type="hidden" name="option" value="com_spidercalendar" />    
		
		<input type="hidden" name="id" value="<?php echo $row->id?>" />        
		<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />        
		<input type="hidden" name="task" value="" />        
		</form>
				<?php		
		
		$bar=JToolBar::getInstance( 'toolbar' );
		$bar->appendButton( 'popup', 'preview', 'Preview', "index.php?option=com_spidercalendar&task=preview_theme&format=row&date=2012-06");
			
		
		
		
		
		
		
		
		
		
}
public static function preview_theme()
{

?>
<script>

cal_width=window.parent.document.getElementById('width').value;

bg_top='#'+window.parent.document.getElementById('bg_top').value;
bg_bottom='#'+window.parent.document.getElementById('bg_bottom').value;
border_color='#'+window.parent.document.getElementById('border_color').value;


text_color_month='#'+window.parent.document.getElementById('text_color_month').value;
color_week_days='#'+window.parent.document.getElementById('text_color_week_days').value;
text_color_other_months='#'+window.parent.document.getElementById('text_color_other_months').value;

text_color_this_month_unevented='#'+window.parent.document.getElementById('text_color_this_month_unevented').value;
evented_color='#'+window.parent.document.getElementById('text_color_this_month_evented').value;
evented_color_bg='#'+window.parent.document.getElementById('bg_color_this_month_evented').value;
//color_arrow_year='#'+window.parent.document.getElementById('arrow_color_year').value;
color_arrow_month='#'+window.parent.document.getElementById('arrow_color_month').value;
sun_days='#'+window.parent.document.getElementById('text_color_sun_days').value;
event_title_color='#'+window.parent.document.getElementById('event_title_color').value;
current_day_border_color='#'+window.parent.document.getElementById('current_day_border_color').value;
cell_border_color='#'+window.parent.document.getElementById('cell_border_color').value;
cell_height=window.parent.document.getElementById('cell_height').value;
popup_width=window.parent.document.getElementById('popup_width').value;
popup_height=window.parent.document.getElementById('popup_height').value;
number_of_shown_evetns=window.parent.document.getElementById('number_of_shown_evetns').value;
sundays_font_size=window.parent.document.getElementById('sundays_font_size').value;
other_days_font_size=window.parent.document.getElementById('other_days_font_size').value;
weekdays_font_size=window.parent.document.getElementById('weekdays_font_size').value;
border_width=window.parent.document.getElementById('border_width').value;
top_height=window.parent.document.getElementById('top_height').value;
bg_color_other_months='#'+window.parent.document.getElementById('bg_color_other_months').value;
sundays_bg_color='#'+window.parent.document.getElementById('sundays_bg_color').value;
weekdays_bg_color='#'+window.parent.document.getElementById('weekdays_bg_color').value;
weekstart=window.parent.document.getElementById('week_start_day').value;
weekday_sunday_bg_color='#'+window.parent.document.getElementById('weekday_sunday_bg_color').value;
border_radius=window.parent.document.getElementById('border_radius').value;
border_radius2=border_radius-border_width;
week_days_cell_height=window.parent.document.getElementById('week_days_cell_height').value;

month_font_size=window.parent.document.getElementById('month_font_size').value;
arrow_size=window.parent.document.getElementById('arrow_size').value;
arrow_size_hover=parseInt(arrow_size);


cell_width=cal_width/7;
if(cell_height=="")
cell_height=70;









var head = document.getElementsByTagName('head')[0],
    style = document.createElement('style'),
    rules = document.createTextNode(
	
'#bigcalendar .cala_arrow a:link, #bigcalendar .cala_arrow a:visited{text-decoration:none;background:none;font-size:'+arrow_size+'px;color:'+color_arrow_month+' }'+

'#bigcalendar td,#bigcalendar tr,  #spiderCalendarTitlesList td,  #spiderCalendarTitlesList tr {border:none;}'+

'#bigcalendar .general_table{border-radius: '+border_radius+'px;}'+

'#bigcalendar .top_table {border-top-left-radius: '+border_radius2+'px;border-top-right-radius: '+border_radius2+'px;}'+

'#bigcalendar .cala_arrow {font-size:'+arrow_size_hover+'px;text-decoration:none;background:none;color:'+color_arrow_month+'}'+ 

'#bigcalendar .cala_day a:link, #bigcalendar .cala_day a:visited {text-decoration:none;background:none;font-size:12px;color:red;}'+ 

'#bigcalendar .cala_day a:hover {text-decoration:none;background:none;}'+

'#bigcalendar .cala_day {border:1px solid '+cell_border_color+';vertical-align:top;}'+ 

'#bigcalendar .weekdays {border:1px solid '+cell_border_color+'}'+

'#bigcalendar .week_days {font-size:'+weekdays_font_size+'px;font-family:arial}'+

'#bigcalendar .calyear_table, .calmonth_table {border-spacing:0;width:100%; }'+

'#bigcalendar .calbg, #bigcalendar .calbg td {text-align:center;	width:14%;}'+

'#bigcalendar .caltext_color_other_months  {color:'+text_color_other_months+';border:1px solid '+cell_border_color+';vertical-align:top;}'+

'#bigcalendar .caltext_color_this_month_unevented {color:'+text_color_this_month_unevented+';}'+

'#bigcalendar .calfont_year {font-family:arial;font-size:24px;font-weight:bold;}'+

'#bigcalendar .calsun_days {color:'+sun_days+';border:1px solid '+cell_border_color+';vertical-align:top;text-align:left;background-color:'+sundays_bg_color+';}'+

'#bigcalendar #month_day {color:'+text_color_month+';font-size:'+month_font_size+'px}'


 
 );

style.type = 'text/css';
if(style.styleSheet)
    style.styleSheet.cssText = rules.nodeValue;
else style.appendChild(rules);
head.appendChild(style);



</script>






<div id="bigcalendar" style="">



<table cellpadding="0" cellspacing="0" id="general_table"  class="general_table"  style="border-spacing:0; margin:0; padding:0;">

    <tr>

        <td width="100%" style=" padding:0; margin:0">

            
              <table  cellpadding="0" id="header_table"  cellspacing="0" border="0" style="border-spacing:0; font-size:12px; margin:0; padding:0;">
				
				

                <tr  style="height:40px;">
				
                    <td id="top_table" class="top_table" align="center" colspan="7" style="padding:0; margin:0;height:20px; " >
					
						
						
						
						
						<?php //YEAR TABLE ?>

                  


				  <table cellpadding="0" cellspacing="0" border="0" align="center" class="calyear_table" id="calyear_table" style="margin:0; padding:0; text-align:center;">

                            <tr>
								
									<td width="15%">
								<div  onclick="" style="cursor:pointer;width:100%; height:35px; background-color: rgba(0,0,0,0.3);"><span id="year_prev" style="position: relative;font-size: 26px;">2012</span></div>
								</td>
								
								<td style="width:100%;vertical-align:center">
								<table style="width:100%;line-height:150%">
								<tr>
									<td class="cala_arrow" width="15%"  style="text-align:right;margin:0px;padding:0px">
									<a  style="text-shadow: 1px 1px 2px black;">&#9668;</a>
													
									</td>
									<td style="text-align:center; margin:0;" width="40%" >

										
										   <span id="month_day"  style="font-family:arial; ;text-shadow: 1px 1px  black;">12 June 2013</span>
										  
											
									</td>	
									<td style="margin:0; padding:0;text-align:left" width="15%"  class="cala_arrow"> 
									<a  style="text-shadow: 1px 1px 2px black;">&#9658;</a>

									</td>
										
									<td width="15%">
								<div   style="cursor:pointer;width:100%; height:35px; background-color: rgba(0,0,0,0.3);"><span id="year_next" style="left: 25%;top: 18%;position: relative;font-size: 26px;">2014</span></div>
								</td>
										
									</tr>
									</table>
									</td>	
                            </tr>
							
                        </table>
					
                    </td>           					
                    <td id="top_td" colspan="7" style="margin:0; padding:0;" >

                    

                    </td>

                </tr>

                <tr align="center"  id="week_days_tr" style="">

				
                    <td id="weekdays1" class="weekdays" style="margin:0; padding:0">

                    <div id="calbottom_border1" class="calbottom_border" style="text-align:center; margin:0; padding:0;"><b class="week_days"> Mo </b></div></td>

                    <td id="weekdays2" class="weekdays" style="margin:0; padding:0">

					
					
					
                   	 <div id="calbottom_border2" class="calbottom_border" style="text-align:center; margin:0; padding:0;"><b class="week_days"> Tu </b></div></td>

				  <td id="weekdays3" class="weekdays" style="margin:0; padding:0">

                   	 <div id="calbottom_border3" class="calbottom_border" style="text-align:center; margin:0; padding:0;"><b class="week_days"> We </b></div></td>

                    <td id="weekdays4" class="weekdays" style=" margin:0; padding:0">

                    	 <div id="calbottom_border4" class="calbottom_border" style="text-align:center;margin:0; padding:0;"><b class="week_days"> Th </b></div></td>

					<td id="weekdays5" class="weekdays" style="margin:0; padding:0">

                   	 <div id="calbottom_border5" class="calbottom_border" style="text-align:center;margin:0; padding:0;"><b class="week_days"> Fr </b></div></td>
					 
                    <td id="weekdays6" class="weekdays" style=" margin:0; padding:0">

                   	 <div id="calbottom_border6" class="calbottom_border" style="text-align:center;margin:0; padding:0;"><b class="week_days"> Sa </b></div></td>

					<td id="weekdays_su" class="weekdays" style=" margin:0; padding:0;">

                    <div id="calbottom_border_su" class="calbottom_border" style="text-align:center;  margin:0; padding:0;"><b class="week_days"> Su </b></div></td>
					
                
				</tr>

                    <?php

//$today=$realtoday;
/*$document = &JFactory::getDocument();
   $document->addScript("media/system/js/modal.js");
   $document->addStyleSheet("media/system/css/modal.css");*/
   $weekday_i=6;
   $month_days=30;
   $last_month_days=31;
   $last_month_days=$last_month_days-$weekday_i+2;
   $percent=1;
   $weekstart='mo';
   $sum=$month_days-8+6;
   
   if($sum % 7 <> 0)

$percent = $percent + 1;

$sum = $sum - ( $sum % 7 );

$percent = $percent + ( $sum / 7 );

$percent=107/$percent;

$array_days=array(11);

$array_days1=$array_days;
$title=array(11=>'<br>
1.   Event1<br>
2.   Event2<br>
3.   Event3<br>
4.   Event4');

$ev_ids=array(11 => '97<br>
98<br>
99<br>
100');
$day_REFERER='';
$month='June';
$year='2012';
$number_of_shown_evetns=2;
?>
<script>
document.write('<tr  id="days"   style=";line-height:15px;">');
</script>         
<?php



for($i=1; $i<6; $i++)

{
?>
<script>
document.write('<td class="caltext_color_other_months" style=" border: 1px solid '+cell_border_color+';vertical-align:top;background-color:'+bg_color_other_months+'"  ><span style="font-size:'+other_days_font_size+'px"><?php echo $last_month_days ?></span></td>');
</script>
<?php
$last_month_days=$last_month_days+1;

}



for($i=1; $i<=$month_days; $i++)

{

if($i==11)
{
$ev_title=explode('</p>',$title[11]);


$k=count($ev_title);
////
$ev_id=explode('<br>',$ev_ids[$i]);
array_pop($ev_id);



}


$dayevent='';

	if(($weekday_i%7==0 and $weekstart=="mo") or ($weekday_i%7==1 and $weekstart=="su"))

	{
	
		if($i==$day_REFERER and $month==$month_REFERER and $year==$year_REFERER )
	
		{ 
		
		?>
		
		<script>
			document.write('<td bgcolor="'+bg_color_selected+'"  class="cala_day" style="padding:0; margin:0;line-height:15px;"><div class="calborder_day" style=" width:'+cell_width+'px; margin:0; padding:0;"><b style="color:'+evented_color+'">'<?php echo $i ?>'</b>');
		</script>
		<?php
		
		$r=0;
		for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			?>
			<script>
			
			document.write('<a class="modal"  style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			</script>
			<?php
			
			$r++;
			}
			
			echo '</td></div>';
	
		}
	
		else
		
		if($i==date('j') and $month==date('F') and $year==date('Y'))
		{
	
			if( in_array ($i,$array_days)){
			
	?>
			<script>
	document.write('<td class="cala_day" style="vertical-align:top;background-color:'+evented_color_bg+';padding:0; margin:0;line-height:15px; border: px solid '+border_day+'"><b style="color:'.$evented_color.';font-size:'.$other_days_font_size.'px">'<?php echo $i ?>'</b>');
	</script>
			<?php
	$r=0;
	for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			{
			?>
			<script>
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+';text-align:center;text-decoration:underline;" href=""> <b>See More</b></a>');
			</script>
			<?php
			}
			
			}
			echo '</td>';
	}
			else
			{
			?>
			<script>
			document.write('<td class="calsun_days" id="calsun_days" style="vertical-align:top;padding:0; font-size:'+sundays_font_size+'px; margin:0;line-height:15px; border: 1px solid '+cell_border_color+'"><b><?php echo  $i ?></b></td>');
			</script>
			<?php
			}
		}

		else
		{
	
			if( in_array ($i,$array_days)){
			?>
	<script>
	document.write('<td class="cala_day" style="vertical-align:top;background-color:'+evented_color_bg+';padding:0; margin:0;line-height:15px; border: px solid '+border_day+'"><b style="color:'+evented_color+';font-size:'+other_days_font_size+'px">'<?php echo $i ?>'</b>');
	</script>
			<?php
	$r=0;
	for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			{
			?>
			<script>
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+';text-align:center;text-decoration:underline;" href=""> <b>See More</b></a>');
			</script>
			<?php
			}
			}
			echo '</td>';
	}
			else
			{
			?>
			<script>
			document.write('<td class="calsun_days" id="calsun_days" style="vertical-align:top;padding:0; font-size:'+sundays_font_size+'px; margin:0;line-height:15px; border: 1px solid '+cell_border_color+'"><b><?php echo  $i ?></b></td>');
			</script>
			<?php
			}
		}

	}
/////////////////////////////////////////////////////////////////////////mec else
	else

		if($i==$day_REFERER and $month==$month_REFERER and $year==$year_REFERER )
	
		{ 	?>
			<script>
			document.write('<td bgcolor="'+bg_color_selected+'" class="cala_day" style="padding:0; margin:0;line-height:15px;"><div class="calborder_day" style=" width:'+cell_width+'px; margin:0; padding:0;"><b style="color:'+evented_color+';font-size:'+other_days_font_size+'px"><?php echo  $i ?></b>');
			</script>
			<?php
			$r=0;
	for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			{
			?>
			<script>
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+';text-align:center;text-decoration:underline;" href=""> <b>See More</b></a>');
			</script>
			<?php
			}
			
			}
			
			echo '</td></div>';
	
		}

		else
	
		{
			if($i==13 and $month=='June' and $year=='2012')
			
		{
				if( in_array ($i,$array_days)){
		
			?>
			<script>	
			document.write('<td class="cala_day" style="vertical-align:top;background-color:'+evented_color_bg+';padding:0; margin:0;line-height:15px; border: 3px solid '+current_day_border_color+'"><b style="color:'+evented_color+';font-size:'+other_days_font_size+'px"><?php echo  $i ?></b>');
			</script>
			<?php
			$r=0;
	for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			{
			?>
			<script>
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+';text-align:center;text-decoration:underline;" href=""> <b>See More</b></a>');

			</script>
			<?php
			}
			else
			{
			?>
			<script>
			</script>
			<?php
			break;
			}
			$r++;
			}
			echo '</td>';
				}
		
				else
				{
			?>
			<script>
				document.write('<td style=" color:'+text_color_this_month_unevented+';padding:0; margin:0; line-height:15px; border: 3px solid '+current_day_border_color+'; vertical-align:top;"><b style="font-size:'+other_days_font_size+'px"><?php echo  $i ?></b></td>');
			</script>
			<?php
			
			}
		}
	
	else
			if( in_array ($i,$array_days)){
	
		?>
			<script>
		document.write('<td class="cala_day" style="vertical-align:top;background-color:'+evented_color_bg+';padding:0; margin:0;line-height:15px;"><b style="color:'+evented_color+';font-size:'+other_days_font_size+'px"><?php echo  $i ?></b>');
		</script>
			<?php
		$r=0;
		for($j=0;$j<$k; $j++)	
			{
			if($r<$number_of_shown_evetns)
			{
			?>
			<script>
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+'; text-decoration:underline;" href="" ><b><br>1.   Event1<br>2.   Event2<br>3.   Event3<br></b></a>');
			document.write('<a class="modal"  rel="{handler: \'iframe\', size: {x: '+popup_width+', y: '+popup_height+'}}" style="background:none;color:'+event_title_color+';text-align:center;text-decoration:underline;" href=""> <b>See More</b></a>');
			</script>
			<?php
			}
			
			}
			echo '</td>';
			}
	
			else
			{
			?>
			<script>
			document.write('<td style=" color:'+text_color_this_month_unevented+';padding:0; margin:0; line-height:15px;border: 1px solid '+cell_border_color+';vertical-align:top;"><b style="font-size:'+other_days_font_size+'px"><?php echo  $i ?></b></td>');
			</script>
			<?php
			
			}
			

	}

	if($weekday_i%7==0 && $i<>$month_days)

	{
	?>
	<script>
	document.write('</tr><tr height="'+cell_height+'" style="line-height:15px">');
	</script>
	<?php
	$weekday_i=0;

	}

	$weekday_i=$weekday_i+1;

}

$weekday_i;

$next_i=1;

if($weekday_i!=1)

for($i=$weekday_i; $i<=7; $i++)

{
if($i!=7)
{
?>

<script>
docuemnt.write('<td class="caltext_color_other_months" style="background-color:'+bg_color_other_months+'"  >'<?php echo $next_i ?>'</td>');
</script>
<?php
}
else
{
?>
<script>
document.write ('<td class="caltext_color_other_months" style="background-color:'+bg_color_other_months+';"  >'<?php echo $next_i ?>'</td>');
</script>
<?php
}
$next_i=$next_i+1;

}

echo '</tr></table>';

?>

                    <input type="text" value="1" name="day" style="display:none" />

          

               

        </td>

    </tr>

</table>
</div>
<script>
								document.getElementById('bigcalendar').style.width=cal_width;
								document.getElementById('general_table').style.width=cal_width;
								document.getElementById('general_table').style.border=border_color+' solid '+border_width;
								document.getElementById('general_table').style.backgroundColor=bg_bottom;
								document.getElementById('header_table').style.width=cal_width;
								document.getElementById('top_table').style.backgroundColor=bg_top;
								document.getElementById('calyear_table').style.width=cal_width;
								document.getElementById('calyear_table').style.height=top_height;
								document.getElementById('year_prev').style.color=bg_top;
								document.getElementById('year_next').style.color=bg_top;


								
								document.getElementById('week_days_tr').style.height=week_days_cell_height;
								document.getElementById('week_days_tr').style.backgroundColor=weekdays_bg_color;
								document.getElementById('top_td').style.backgroundColor=bg_top;
								
								for(var i=1;i<=6;i++){
								document.getElementById('weekdays'+i).style.width=cell_width;
								document.getElementById('weekdays'+i).style.color=color_week_days;
								document.getElementById('calbottom_border'+i).style.width=cell_width;
								}
								
								document.getElementById('weekdays_su').style.width=cell_width;
								document.getElementById('weekdays_su').style.color=color_week_days;
								document.getElementById('weekdays_su').style.backgroundColor=weekday_sunday_bg_color;
								document.getElementById('days').style.height=cell_height;
								
								</script>







<?php
}


/////////////////////////// RECENT EVENTS  MODULE/////////////////////////////

public static function module_event($rows, $pageNav, $lists)
{
	JHTML::_('behavior.tooltip');
	JHTML::_('behavior.modal');
    JHTML::_('behavior.calendar');
		?>

<script type="text/javascript">

Joomla.submitbutton= function (pressbutton){

var form = document.adminForm;

if (pressbutton == 'cancel') 

{

submitform( pressbutton );

return;

}

submitform( pressbutton );

}

Joomla.tableOrdering=function( order, dir, task ) {

    var form = document.adminForm;

    form.filter_order_playlist.value     = order;

    form.filter_order_Dir_playlist.value = dir;

    submitform( task );

}

function xxx()
{

	var id =[];
	var title =[];
	
	
	
	for(i=0; i<<?php echo count($rows) ?>; i++)
		if(document.getElementById("v"+i))
			if(document.getElementById("v"+i).checked)
			{
				id.push(document.getElementById("v"+i).value);
				title.push(document.getElementById("title_"+i).value);
				
				
			}
	window.parent.jSelectEvents(id, title);
	
}

window.addEvent('domready', function() {Calendar.setup({
        inputField     :    "st_d",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "st_d_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           

        singleClick    :    true
    });});
window.addEvent('domready', function() {Calendar.setup({
        inputField     :    "en_d",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "en_d_img",  // trigger for the calendar (button ID)
        align          :    "Tl",           

        singleClick    :    true
    });});



</script>



		
		<form action="index.php?option=com_spidercalendar&task=module_event&cal=<?php echo JRequest::getVar('cal')?>&tmpl=component" method="post" name="adminForm" id="adminForm">
    
		<table>
		<tr>
						<td align="left" width="100%">

<?php echo JText::_( 'Title' ); ?>:
<input type="text" name="search_note" id="search_note" value="<?php echo $lists['search_note'];?>"  class="text_area" onchange="document.adminForm.submit();" />
   <?php echo JText::_( 'From' ); ?>:
<input name="startdate" id="st_d" type="text" value="<?php echo $lists['startdate'];?>" onchange="document.adminForm.submit();" /> <img class="calendar" src="templates/hathor/images/system/calendar.png" alt="calendar" id="st_d_img" / > 
<?php echo JText::_( 'To' ); ?>:  
<input name="enddate" id="en_d" type="text" value="<?php echo $lists['enddate'];?>" onchange="document.adminForm.submit();" /> <img class="calendar" src="templates/hathor/images/system/calendar.png" alt="calendar" id="en_d_img" / > 
 <button onclick="this.form.submit();">
<?php echo JText::_( 'Go' ); ?></button>
				<button onclick="document.getElementById('search_note').value='';document.getElementById('st_d').value='';document.getElementById('en_d').value='';this.form.submit();">
<?php echo JText::_( 'Reset' ); ?></button>			</td>
			
			<td align="right" width="100%">
            <button onclick="xxx();" style="width:98px; height:34px; background:url(<?php echo JURI::root() ?>modules/mod_spidercalendar_upcoming_events/images/add_but.png) no-repeat;border:none;cursor:pointer;">&nbsp;</button>           
             </td>
		</tr>
		</table>  
		


        
   <table class="table table-striped">
    <thead>
    	<tr>
            <th width="30"><?php echo '#'; ?></th>
            <th width="20">
            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this, 'v')">
            </th>
            <th width="40" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></td>
            
            <th><?php echo JHTML::_('grid.sort', 'Title', 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>
			<th><?php echo JHTML::_('grid.sort', 'Date', 'event_day', @$lists['order_Dir'], @$lists['order'] ); ?></th>
            
            <th nowrap="nowrap" width="70"><?php echo JHTML::_('grid.sort',   'Published', 'published',@$lists['order_Dir'], @$lists['order'] ); ?></th>
       </tr>
    </thead>
	<tfoot>
		<tr>
			<td colspan="11">
			 <?php echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
                
    <?php
	function published_icon( &$row, $i, $task, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' ){
        $img     = $row->published ? $imgY : $imgX;
        $task     = $row->published ? 'unpublish_'.$task : 'publish_'.$task;
        $alt     = $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
        $action = $row->published ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );
 
        $href = '
        <img src="templates/hathor/images/admin/'. $img .'" border="0" alt="'. $alt .'" />'
        ;
 
        return $href;
    
}
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
		$row = &$rows[$i];
		$checked 	= JHTML::_('grid.id', $i, $row->id);
		$published 	= JHTML::_('grid.published', $row, $i); 
 	$published 	= published_icon($row, $i, 'event'); 
		
		
?>
        <tr class="<?php echo "row$k"; ?>">
        	<td align="center"><?php echo $i+1?></td>
        	<td>
            <input type="checkbox" id="v<?php echo $i?>" value="<?php echo $row->id; ?>" />
            <input type="hidden" id="title_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->title);?>" />
			<input type="hidden" id="day_<?php echo $i?>" value="<?php echo  htmlspecialchars($row->date);?>" />
            
            </td>
			
        	<td align="center"><?php echo $row->id?></td>
        	<td><a style="cursor: pointer;" onclick="window.parent.jSelectEvents(['<?php echo $row->id?>'],['<?php echo htmlspecialchars(addslashes($row->title));?>'])"><?php echo $row->title?></a></td>            
           <td><?php echo $row->date?></td>
        	<td align="center"><?php echo $published?></td>            
        </tr>
        <?php
		$k = 1 - $k;
	}
	?>
    </table>

    <input type="hidden" name="option" value="com_spidercalendar">
    <input type="hidden" name="task" value="module_event">    
    <input type="hidden" name="boxchecked" value="0"> 
	<input type="hidden" name="filter_order" 
value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />       
    </form>
    <?php
	}
	







}


function published( &$row, $i, $task, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' ){
        $img     = $row->published ? $imgY : $imgX;
        $task     = $row->published ? 'unpublish_'.$task : 'publish_'.$task;
        $alt     = $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
        $action = $row->published ? JText::_( 'Unpublish Item' ) : JText::_( 'Publish item' );
 
        $href = '
        <a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
        <img src="templates/hathor/images/admin/'. $img .'" border="0" alt="'. $alt .'" /></a>'
        ;
 
        return $href;
    
}
?>