<?php
/*------------------------------------------------------------------------
# plg_addcustomcss - Add Custom CSS
# ------------------------------------------------------------------------
# version 1.1.1
# author Impression eStudio
# copyright Copyright (C) 2012 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.plugin.plugin' );

class plgSystemAddCustomCSS extends JPlugin
{	
	function plgSystemAddCustomCSS( &$subject, $params )
	{
		parent::__construct($subject, $params);

	}
	
	function onBeforeCompileHead()
	{
		$document = JFactory::getDocument();

		if (strcmp(substr(JURI::base(), -15), "/administrator/")!=0)	// Apply styles only to front-end.
		{
			// **********************************************************************
			// Custom CSS 1.
			// **********************************************************************
			if (strcmp($this->params->get('custom_css_file_path_1'),'')!=0)
			{
				ob_start();
				include($this->params->get('custom_css_file_path_1'));
				$styles = ob_get_contents();
				
				// Remove comments.
				if ($this->params->get('remove_comments'))
				{
					$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
				}
				
				// Minimize.
				if ($this->params->get('minimize'))
				{
					$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
					$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
					$styles = trim($styles);		// Trim the string of leading and trailing space.
				}
				
				// Convert short absolute paths to full absolute paths.
				if ($this->params->get('full_absolute_paths'))
				{
					$styles = str_replace('url(/', 'url('.JURI::base(), $styles);
					$styles = str_replace("url('/", "url('".JURI::base(), $styles);
					$styles = str_replace('url("/', 'url("'.JURI::base(), $styles);
				}
				
				ob_clean();
				//$document->addStyleDeclaration($styles);
				$document->addCustomTag('<style type="text/css">'.$styles.'</style>');
			}
			
			// **********************************************************************
			// Custom CSS 2.
			// **********************************************************************
			if (strcmp($this->params->get('custom_css_file_path_2'),'')!=0)
			{
				ob_start();
				include($this->params->get('custom_css_file_path_2'));
				$styles = ob_get_contents();
				
				// Remove comments.
				if ($this->params->get('remove_comments'))
				{
					$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
				}
				
				// Minimize.
				if ($this->params->get('minimize'))
				{
					$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
					$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
					$styles = trim($styles);		// Trim the string of leading and trailing space.
				}
				
				// Convert short absolute paths to full absolute paths.
				if ($this->params->get('full_absolute_paths'))
				{
					$styles = str_replace('url(/', 'url('.JURI::base(), $styles);
					$styles = str_replace("url('/", "url('".JURI::base(), $styles);
					$styles = str_replace('url("/', 'url("'.JURI::base(), $styles);
				}
				
				ob_clean();
				//$document->addStyleDeclaration($styles);
				$document->addCustomTag('<style type="text/css">'.$styles.'</style>');
			}
			
			// **********************************************************************
			// Custom CSS 3.
			// **********************************************************************
			if (strcmp($this->params->get('custom_css_file_path_3'),'')!=0)
			{
				ob_start();
				include($this->params->get('custom_css_file_path_3'));
				$styles = ob_get_contents();
				
				// Remove comments.
				if ($this->params->get('remove_comments'))
				{
					$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
				}
				
				// Minimize.
				if ($this->params->get('minimize'))
				{
					$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
					$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
					$styles = trim($styles);		// Trim the string of leading and trailing space.
				}
				
				// Convert short absolute paths to full absolute paths.
				if ($this->params->get('full_absolute_paths'))
				{
					$styles = str_replace('url(/', 'url('.JURI::base(), $styles);
					$styles = str_replace("url('/", "url('".JURI::base(), $styles);
					$styles = str_replace('url("/', 'url("'.JURI::base(), $styles);
				}
				
				ob_clean();
				//$document->addStyleDeclaration($styles);
				$document->addCustomTag('<style type="text/css">'.$styles.'</style>');
			}
		}
	}
}
