<?php

 /**
 * @package Spider Calendar Lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/

defined('_JEXEC') or die('Restricted access');

class Tablespidercalendar_theme extends JTable
{
var $id = null;
var $title = null;
var $width = null;
var $bg_top = null;
var $bg_bottom = null;
var $border_color = null;
var $text_color_year = null;
var $text_color_month = null;
var $text_color_week_days = null;
var $text_color_other_months = null;
var $text_color_this_month_unevented = null;
var $text_color_this_month_evented = null;
var $bg_color_this_month_evented = null;
var $arrow_color_year = null;
var $arrow_color_month = null;
var $text_color_sun_days = null;
var $title_color = null;
var $title_font_size = null;
var $title_font = null;
var $title_style = null;
var $date_color = null;
var $date_size = null;
var $date_font = null;
var $date_style = null;
var $event_title_color = null;
var $current_day_border_color = null;
var $cell_border_color = null;
var $cell_height = null;
var $next_prev_event_bgcolor = null;
var $next_prev_event_arrowcolor = null;
var $show_event_bgcolor = null;
var $popup_width = null;
var $popup_height = null;
var $number_of_shown_evetns = null;
var $sundays_font_size = null;
var $other_days_font_size = null;
var $weekdays_font_size = null;
var $border_width = null;
var $top_height = null;
var $bg_color_other_months = null;
var $sundays_bg_color = null;
var $weekdays_bg_color = null;
var $week_start_day = null;
var $weekday_sunday_bg_color = null;
var $border_radius = null;
var $week_days_cell_height = null;
var $year_font_size = null;
var $month_font_size = null;
var $arrow_size = null;
var $next_month_text_color = null;
var $prev_month_text_color = null;
var $next_month_arrow_color = null;
var $prev_month_arrow_color = null;
var $next_month_font_size = null;
var $prev_month_font_size = null;
var $month_type = null;
var $date_format = null;
var $show_time = null;
var $show_repeat = null;

var $date_bg_color = null;
var $event_bg_color1 = null;
var $event_bg_color2 = null;
var $event_num_bg_color1 = null;
var $event_num_bg_color2 = null;
var $event_num_color = null;

var $date_font_size = null;

var $event_num_font_size = null;
var $event_table_height = null;
var $date_height = null;
var $ev_title_bg_color = null;
var $day_month_font_size = null;
var $week_font_size = null;

var $day_month_font_color = null;
var $week_font_color = null;

var $views_tabs_bg_color= Null;

var $views_tabs_text_color= Null;

var $views_tabs_font_size= Null;


function __construct(&$db)
{
parent::__construct('#__spidercalendar_theme','id',$db);
}
}

?>