<?php

 /**
 * @package Spider Calendar lite
 * @author Web-Dorado
 * @copyright (C) 2011 Web-Dorado. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 **/
 
class JElementThemeselect extends JElement
{
function fetchElement($name, $value, &$node, $control_name)
{
        ob_start();
        static $embedded;
                if(!$embedded)
        {

            $embedded=true;

        }
            ?>
     <label>
<select name="<?php echo $control_name."[".$name."]";?>" id="<?php echo  $control_name.$name ?>" onchange="change()">
<option value="custom" <?php if($value=='custom') echo 'selected="selected"'; ?>>Custom</option>
<option value="blue" <?php if($value=='blue') echo 'selected="selected"'; ?>>Blue</option>
<option value="red" <?php if($value=='red') echo 'selected="selected"'; ?>>Red</option>
<option value="orange" <?php if($value=='orange') echo 'selected="selected"'; ?>>Orange</option>
<option value="green" <?php if($value=='green') echo 'selected="selected"'; ?>>Green</option>
<option value="white" <?php if($value=='white') echo 'selected="selected"'; ?>>White</option>
<option value="shiny_blue" <?php if($value=='shiny_blue') echo 'selected="selected"'; ?>>Shiny Blue</option>

</select>

    </label>
    <script type="text/javascript">
	
	function change()
	{
	alert()
		x=document.getElementById('paramscalendar_style').selectedIndex;
		
		switch(x)
		{
		case 1:
			document.getElementById('paramsborder_color').value="00adef";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			
			document.getElementById('paramstext_color_month').value="FFFFFF";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="FFFFFF";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="000000";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="00adef";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="FFFFFF";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="00adef";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="FFFFFF";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="00adef";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="FFFFFF";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="00adef";
			document.getElementById('paramsborder_day').color.importColor();
			
			break;
		case 2:
			document.getElementById('paramsborder_color').value="ed1b24";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			
			document.getElementById('paramstext_color_month').value="FFFFFF";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="FFFFFF";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="000000";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="ed1b24";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="FFFFFF";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="ed1b24";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="FFFFFF";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="ed1b24";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="FFFFFF";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="ed1b24";
			document.getElementById('paramsborder_day').color.importColor();
			
			break;
		case 3:
			document.getElementById('paramsborder_color').value="f6821f";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			document.getElementById('paramstext_color_month').value="FFFFFF";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="FFFFFF";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="000000";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="f6821f";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="FFFFFF";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="f6821f";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="FFFFFF";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="f6821f";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="FFFFFF";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="f6821f";
			document.getElementById('paramsborder_day').color.importColor();
			
			break;
		case 4:
			document.getElementById('paramsborder_color').value="00a650";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			
			document.getElementById('paramstext_color_month').value="FFFFFF";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="FFFFFF";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="000000";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="00a650";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="FFFFFF";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="00a650";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="FFFFFF";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="00a650";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="FFFFFF";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="00a650";
			document.getElementById('paramsborder_day').color.importColor();
			
			break;
		case 5:
			document.getElementById('paramsborder_color').value="ffffff";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			
			document.getElementById('paramstext_color_month').value="000000";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="000000";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="000000";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="000000";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="FFFFFF";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="999999";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="000000";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="FF0000";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="ffffff";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="999999";
			document.getElementById('paramsborder_day').color.importColor();
			
			break;
			
		case 6:
			document.getElementById('paramsborder_color').value="005478";
			document.getElementById('paramsborder_color').color.importColor();
			
			
			document.getElementById('paramstext_color_month').value="FFFFFF";
			document.getElementById('paramstext_color_month').color.importColor();
			
			document.getElementById('paramstext_color_week_days').value="2F647D";
			document.getElementById('paramstext_color_week_days').color.importColor();
			
			document.getElementById('paramstext_color_other_months').value="939699";
			document.getElementById('paramstext_color_other_months').color.importColor();
			
			document.getElementById('paramstext_color_this_month_unevented').value="989898";
			document.getElementById('paramstext_color_this_month_unevented').color.importColor();
			
			document.getElementById('paramstext_color_this_month_evented').value="FBFFFE";
			document.getElementById('paramstext_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_this_month_evented').value="005478";
			document.getElementById('paramsbg_color_this_month_evented').color.importColor();
			
			document.getElementById('paramsbg_color_selected').value="005478";
			document.getElementById('paramsbg_color_selected').color.importColor();
			
			document.getElementById('paramsarrow_color').value="CED1D0";
			document.getElementById('paramsarrow_color').color.importColor();
			
			document.getElementById('paramstext_color_sun_days').value="989898";
			document.getElementById('paramstext_color_sun_days').color.importColor();
			
			document.getElementById('paramstext_color_selected').value="FFFFFF";
			document.getElementById('paramstext_color_selected').color.importColor();
			
			document.getElementById('paramsborder_day').value="005478";
			document.getElementById('paramsborder_day').color.importColor();
			
			
			
			document.getElementById('weekdays_bg_color').value="D6D6D6";
			document.getElementById('weekdays_bg_color').color.importColor();
			
			
			document.getElementById('weekday_su_bg_color').value="B5B5B5";
			document.getElementById('weekday_su_bg_color').color.importColor();
				
			document.getElementById('cell_border_color').value="D2D2D2";
			document.getElementById('cell_border_color').color.importColor();
			
			break;	
			
			
		}
	}
    </script>
        <?php

        $content=ob_get_contents();

        ob_end_clean();
        return $content;

    }
	}
	
	?>