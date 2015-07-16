<?php
/*------------------------------------------------------------------------
# mod_add_custom_css - Add Custom CSS
# ------------------------------------------------------------------------
# version 1.1.1
# author Impression eStudio
# copyright Copyright (C) 2012 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die('Direct access to this location is not allowed.');

jimport('joomla.form.formfield');

class JFormFieldSpacerinfo extends JFormField 
{
	protected $type = 'Spacerinfo';

	public function getInput()
	{
		$output = "";
		$output .= '<style type="text/css">
		div.panel label.hasTip {
			font-weight: bold;
			color:#333333;
		}
		
		div.panel input[type=text] {
			width:50%;
		}
		
		.acc-info td {
			vertical-align:top;
		}
		.acc-info td:first-child {
			width:120px;
			padding-top:5px;
		}
		.acc-info td:first-child img {
			-moz-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			-webkit-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			-o-box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
			margin-right:20px;
		}
		</style>';
		
		$output .= '
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="acc-info">
			<tr>
				<td><img src="../plugins/system/addcustomcss/addcustomcss/images/logo.jpg" width="100" height="100" /></td>
				<td><strong>Name</strong>: Add Custom CSS <br />
					<strong>Version</strong>: 1.1.1 <br />
					<strong>Author</strong>: Impression eStudio <br />
					<strong>Website</strong>: <a href="http://joomla.impression-estudio.gr" target="_blank">http://joomla.impression-estudio.gr</a> <br />
					<strong>Contact</strong>: <a href="mailto:info@impression-estudio.gr">info@impression-estudio.gr</a> <br />
					<strong>Demo</strong>: <a href="../plugins/system/addcustomcss/addcustomcss/images/demo.png" target="_blank">Click here</a> <br />
					<strong>Help</strong>: <a href="http://joomla.impression-estudio.gr/add-custom-css/index.php" target="_blank">Click here</a>
				</td>
			</tr>
		</table>
		<p>&nbsp;</p>
		<p><strong>How it works</strong>: This extension adds your custom CSS file as inline code at the end of the &lt;head&gt; element. This method allows the use of the features a) comments removal, b) minimization, c) full absolute paths.</p>
		<p><strong>Good practice</strong>: The following lines will help you to make your CSS code to be working without additional changes to the localhost and the official server and to be fully compatible with SEF URLs.</p>
		<p>When using CSS properties in your custom CSS file that define the path to an image (e.g. "background", "background-image", "border-image", "list-style-image") then it is better to <strong>use the short version of the absolute URL paths</strong>. For example do not use paths like "../../path/to/the/image.jpg" (relative path) or "http://www.domain.com/images/backgrounds/bg.jpg" (full absolute path) but <strong>use paths like "/templates/my-template/images/bg.jpg" (short absolute path) starting the path from the Joomla installation folder</strong>. This way has the benefit that it is easier to create paths to the images starting always from the Joomla installation folder than calculating how many folders to go back when using relative paths. Finally, by enabling the field "Full Absolute Paths" all the short absolute paths are automatically transformed to full absolute paths. The result is that your CSS code includes paths to the images that work without problem a) to the localhost b) to the official server and c) when your website is using SEF URLs.</p>
		<p>It is not necessary to follow this practice. You can still use whichever type of path you want: a) relative paths, b) short absolute paths, c) full absolute paths.</p>
		';
		
		return $output;
	}
}
?>