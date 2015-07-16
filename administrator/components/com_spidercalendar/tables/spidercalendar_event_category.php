<?php

 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class Tablespidercalendar_event_category extends JTable
{
var $id = null;
var $title = null;
var $color = null;
var $calendar_id = null;
var $published = null;
var $description = null;

function __construct(&$db)
{
parent::__construct('#__spidercalendar_event_category','id',$db);
}
}

?>