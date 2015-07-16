<?php
 
 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined( '_JEXEC' ) or die( 'Restricted access' );

$task=JRequest::getVar('task');
switch($task)
{

case 'publish_event':
    	changeNote(1);
   		break;

   case 'unpublish_event':
    	changeNote(0);
    	break;		
case 'remove_event':
   	 	removeNote();
  		break;


case 'back':   	 	back();  		break;}

		$session =JFactory::getSession();
		$rows=$this->rows;
		$pageNav=$this->pageNav;
		$lists=$this->lists;
		$option=$this->option;
		$user =JFactory::getUser();
		$module_id=JRequest::getVar( "module_id");
		$calendar_id=JRequest::getVar( "calendar");

$calendar =JTable::getInstance('spidercalendar_calendar', 'Table');
	// load the row from the db table

$calendar->load( $calendar_id);
$allow_publish=$calendar->allow_publish;
		
		JHTML::_('behavior.tooltip');	
		JHTML::_('behavior.calendar');
	jimport('joomla.form.form');

$form = JForm::getInstance('naForm',dirname(__FILE__)."/datefields.xml");


$mainframe = JFactory::getApplication();

$db		=JFactory::getDBO();
$query = "SELECT * FROM #__spidercalendar_calendar where id=".$calendar_id."";
	$db->setQuery( $query );
	
	$cal = $db->loadObjectList();


$user =JFactory::getUser();

$userGroups = $user->get('groups');


$access=explode(',',$cal[0]->gid);
$userGroups=array_merge(array(),$userGroups);
if(!$userGroups)
$userGroups=array(1);

foreach($userGroups as $key=>$value)
{

if (!in_array($userGroups[$key],$access) ){
	$mainframe->redirect(JURI::root(),'access denied','error');
break;
}
}




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
				</script>



<style>
#admintable_id table , td, tr
{
border:0px;
}

#admintable_id tr, td
{
padding-left: 0px !important;
}


#gag a
{
text-decoration: none !important;
}
#gag a:hover
{
background-color:none !important;
}


</style>

<table id='admintable_id'>
	<tr>
		<td>
			<a href="index.php?option=com_spidercalendar&view=add_event&calendar=<?php echo JRequest::getVar('calendar'); ?>&module_id=<?php echo $module_id ?>">
			<button style="font-weight: bold;height: 30px;width: 130px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;background-image: url(administrator/components/com_spidercalendar/elements/new.png);background-repeat: no-repeat;background-position: 4px 2px;"><?php echo JText::_('NEW_EVENT') ?></button>
			</a>
		</td>
		
		<?php if($allow_publish==1) { ?>
		<td>
			<a  onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('Please make a selection from the list to publish');}else{  submitbutton('publish_event')}" class="toolbar">
			<button style="font-weight: bold;height: 30px;width: 170px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;background-image: url(administrator/components/com_spidercalendar/elements/publish.png);background-repeat: no-repeat;background-position: 4px 2px;margin-left: 10px;"><?php echo JText::_('PUBLISH_EVENT') ?></button>
			</a>
		</td>
		<td>	
			<a  onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('Please make a selection from the list to unpublish');}else{  submitbutton('unpublish_event')}" class="toolbar">
			<button style="font-weight: bold;height: 30px;width: 181px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;background-image: url(administrator/components/com_spidercalendar/elements/unpublish.png);background-repeat: no-repeat;background-position: 4px 2px;margin-left: 10px;"><?php echo JText::_('UNPUBLISH_EVENT') ?></button>
			</a>	
		</td>
		
		<?php } ?>
		<td>	
			<a   onclick="javascript:if(document.adminForm.boxchecked.value==0){alert('Please make a selection from the list to delete');}else{if(confirm('Are you sure you want to delete?')){submitbutton('remove_event');}}" class="toolbar">
			<button style="font-weight: bold;height: 30px;width: 170px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;background-image: url(administrator/components/com_spidercalendar/elements/delete.png);background-repeat: no-repeat;background-position: 4px 3px;margin-left: 10px;"><?php echo JText::_('DELETE_EVENT') ?></button>
			</a>
		</td>
	</tr>
</table>


	<form action="index.php?option=com_spidercalendar&view=show_events&calendar=<?php echo JRequest::getVar('calendar'); ?>" method="post" name="adminForm" id="adminForm">
    
		<table id='admintable_id2'>
		<tr>
			<td align="left" width="100%">

<?php echo JText::_( 'TITLE' ); ?>:
 <input style="height: 28px;background-color: #ebebeb;border: none;font-size: 15px;color:#606060" type="text" name="search" id="search" value="<?php echo $lists['search'];?>"  class="text_area" onchange="document.adminForm.submit();" />      
 
 


 
 <?php echo JText::_( 'FROM' ); ?>: 
 <input style="height: 28px;background-color: #ebebeb;border: none;font-size: 15px;color:#606060" type="text" name="startdate" id="st_d" value="<?php echo $lists['startdate'] ?>" size="15" readonly="readonly">
 <img class="calendar" id="st_d_img" src="administrator/components/com_spidercalendar/elements/calendar.png" alt="calendario" style="position:relative; top: -5px;left: -30px" />
<?php echo JText::_( 'TO' ); ?>: 
<input style="height: 28px;background-color: #ebebeb;border: none;font-size: 15px;color:#606060" type="text" name="enddate" id="en_d" value="<?php echo $lists['enddate'] ?>" size="15" readonly="readonly">
<img class="calendar" id="en_d_img" src="administrator/components/com_spidercalendar/elements/calendar.png" alt="calendario" style="position:relative; top: -5px;left: -30px" /> 

 
				<button style="font-weight: bold;height: 25px;width: 30px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;" onclick="this.form.submit();">
<?php echo JText::_( 'GO' ); ?></button>
				<button style="font-weight: bold;height: 25px;width: 50px;background-color: #f4f4f4;border: 1px solid #d4d4d4;color: #606060;" onclick="document.getElementById('search').value='';document.getElementById('st_d').value='';document.getElementById('en_d').value=''">
<?php echo JText::_( 'RESET' ); ?></button>			</td>
		</tr>
		</table> 
    
        
    <table class="adminlist">
    <thead id="gag">
    	<tr style="background-color: #F5F5F5;">
        	<th width="20">
            <input type="checkbox" name="toggle"
 value="" onclick="Joomla.checkAll(this)">
            </th>
			 <th width="25" style="text-align:center" class="title"><?php echo JHTML::_('grid.sort',   'ID', 'id', @$lists['order_Dir'], @$lists['order'] ); ?></th>

			 <th width="250" style="text-align:center" nowrap="nowrap"><?php echo JHTML::_('grid.sort', JText::_( "TITLE" ), 'title', @$lists['order_Dir'], @$lists['order'] ); ?></th>

            <th width="80" style="text-align:center" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   JText::_( 'Date' ), 'date', @$lists['order_Dir'], @$lists['order'] ); ?></th>
           		 <th width="80" style="text-align:center" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  JText::_( 'TIME' ), 'time', @$lists['order_Dir'], @$lists['order'] ); ?></th>
             <th width="80" style="text-align:center" nowrap="nowrap"><?php echo JHTML::_('grid.sort',   'Category', 'cattitle', @$lists['order_Dir'], @$lists['order'] ); ?></th> 				     
			<th width="20" style="text-align:center" nowrap="nowrap"><?php if($allow_publish==1) echo JHTML::_('grid.sort',   JText::_( 'PUBLISHED' ), 'published',@$lists['order_Dir'], @$lists['order'] ); ?>
			</th>
			
        </tr>
    </thead>
	<tfoot align="center">
		<tr>
			<td colspan="11" style="text-align:center;">
			  
			 <?php  echo $pageNav->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
                
    <?php
	
    $k = 0;
	for($i=0, $n=count($rows); $i < $n ; $i++)
	{
			$row = &$rows[$i];
			$checked 	= JHTML::_('grid.id', $i, $row->id);
			$published 	= published($row, $i, 'event'); 
			// prepare link for id column
			$link 		= JRoute::_( 'index.php?option=com_spidercalendar&view=edit_event&calendar='.JRequest::getVar('calendar').'&module_id='.JRequest::getVar('module_id').'&cid[]='. $row->id );
			echo '<tr class="row'.$k.'">
        	<td>'.$checked.'</td>
			<td align="center"><a href="'.$link.'">'.$row->id.'</a></td>

			<td align="center"><a href="'.$link.'">'.$row->title.'</a></td>

			<td align="center">'.$row->date;
			
			if($row->date_end!="" and $row->date_end!='0000-00-00')
			echo "  -  ".$row->date_end."";
			
			echo '</td>
			<td align="center">'.$row->time.'</td>			<td align="center">'.$row->cattitle.'</td>';
           if($allow_publish==1)
			echo'
			<td align="center">'.$published.'</td> ';           
        
		echo '</tr>';
$k = 1 - $k;
	}
	?>
    
    </table>
    <input type="hidden" name="option" value="com_spidercalendar">
    <input type="hidden" name="task" value="event">    
    <input type="hidden" name="boxchecked" value="0"> 
	<input type="hidden" name="filter_order" 
value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="" />  
    <input type="hidden" name="calendar" value="<?php echo JRequest::getVar('calendar'); ?>" />  


    

    </form>
<?php
 
function published( &$row, $i, $task, $imgY = "tick.png", $imgX = 'publish_x.png', $prefix='' ){
        $img     = $row->published ? $imgY : $imgX;
        $task     = $row->published ? 'unpublish_'.$task : 'publish_'.$task;
        $alt     = $row->published ? JText::_( 'PUBLISHED' ) : JText::_( 'UNPUBLISHED' );
        $action = $row->published ? JText::_( 'UNPUBLISH_ITEM' ) : JText::_( 'PUBLISH_ITEM' );
 
        $href = '
        <a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
        <img src="administrator/templates/hathor/images/admin/'. $img .'" border="0" alt="'. $alt .'" /></a>'
        ;
 
        return $href;
    
}

function changeNote( $state=0 )
{
  $mainframe = JFactory::getApplication();

  // Initialize variables
  $db 	=JFactory::getDBO();

  // define variable $cid from GET
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );	
  JArrayHelper::toInteger($cid);

  // Check there is/are item that will be changed. 
  //If not, show the error.
  if (count( $cid ) < 1) {
    $action = $state ? 'publish' : 'unpublish';
    JError::raiseError(500, JText::_( 'Select an item 
    to' .$action, true ) );
  }

  // Prepare sql statement, if cid more than one, 
  // it will be "cid1, cid2, cid3, ..."
  $cids = implode( ',', $cid );

  $query = 'UPDATE #__spidercalendar_event'
  . ' SET published = ' . (int) $state
  . ' WHERE id IN ( '. $cids .' )'
  ;
  // Execute query
  $db->setQuery( $query );
  if (!$db->query()) {
    JError::raiseError(500, $db->getErrorMsg() );
  }

  if (count( $cid ) == 1) {
    $row =JTable::getInstance('spidercalendar_event', 'Table');
    $row->checkin( intval( $cid[0] ) );
  }

  // After all, redirect to front page
  $mainframe->redirect( 'index.php?option=com_spidercalendar&view=show_events&calendar='.JRequest::getVar('calendar'),'','message' );
}

function removeNote()
{

  $mainframe = JFactory::getApplication();
  // Initialize variables	
  $db =JFactory::getDBO();
  // Define cid array variable
  $cid = JRequest::getVar( 'cid' , array() , '' , 'array' );
  // Make sure cid array variable content integer format
  JArrayHelper::toInteger($cid);

  // If any item selected
  if (count( $cid )) {
    // Prepare sql statement, if cid array more than one, 
    // will be "cid1, cid2, ..."
    $cids = implode( ',', $cid );
    // Create sql statement
    $query = 'DELETE FROM #__spidercalendar_event WHERE id IN ( '. $cids .' )';
    // Execute query
    $db->setQuery( $query );
    if (!$db->query()) {
      echo "<script> alert('".$db->getErrorMsg(true)."'); 
      window.history.go(-1); </script>\n";
    }			
  }

  // After all, redirect again to frontpage
  $mainframe->redirect( "index.php?option=com_spidercalendar&view=show_events&calendar=".JRequest::getVar('calendar'),'','message' );
}function back(){	$mainframe = JFactory::getApplication();;	$link = 'index.php/calendar';	$mainframe->redirect($link,'','message');}

?>   
 
 
   
   
