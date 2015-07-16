<?php

 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class Tablespidercalendar_calendar extends JTable
{
var $id = null;
var $title = null;
var $published = null;
var $gid = null;
var $allow_publish = null;
var $time_format = null;
var $def_year = null;
var $def_month = null;
var $get_email = null;
var $email = null;



function __construct(&$db)
{
parent::__construct('#__spidercalendar_calendar','id',$db);
}
}

?>