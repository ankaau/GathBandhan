<?php

 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class Tablespidercalendar_event extends JTable
{
var $id = null;
var $calendar = null;
var $category = null;
var $date = null;
var $date_end = null;
var $time = null;
var $title = null;
var $text_for_date = null;
var $userID = null;
var $repeat_method = null;
var $repeat = null;
var $week = null;
var $month = null;
var $month_type = null;
var $monthly_list = null;
var $month_week = null;
var $year_month = null;
var $published = null;
function __construct(&$db)
{
parent::__construct('#__spidercalendar_event','id',$db);
}
}

?>