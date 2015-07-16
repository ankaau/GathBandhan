<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class Tablespidercalendar extends JTable
{
var $id = null;
var $date = null;
var $date_end = null;
var $time = null;
var $title = null;
var $text_for_date = null;
var $published = null;
function __construct(&$db)
{
parent::__construct('#__spidercalendar','id',$db);
}
}

?>