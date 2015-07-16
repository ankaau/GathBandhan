<?php
/*------------------------------------------------------------------------
# mod_add_custom_css - Add Custom CSS
# ------------------------------------------------------------------------
# version 1.1.1
# author Impression eStudio
# copyright Copyright (C) 2013 Impression eStudio. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://joomla.impression-estudio.gr
# Technical Support: info@impression-estudio.gr
-------------------------------------------------------------------------*/

// No direct access.
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

// Main code.
$document = JFactory::getDocument();

// **********************************************************************
// Custom CSS 1.
// **********************************************************************
if (strcmp($params->get('custom_css_file_path_1'),'')!=0)
{
	ob_start();
	include($params->get('custom_css_file_path_1'));
	$styles = ob_get_contents();
	
	// Remove comments.
	if ($params->get('remove_comments'))
	{
		$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
	}
	
	// Minimize.
	if ($params->get('minimize'))
	{
		$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
		$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
		$styles = trim($styles);		// Trim the string of leading and trailing space.
	}
	
	// Convert short absolute paths to full absolute paths.
	if ($params->get('full_absolute_paths'))
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
if (strcmp($params->get('custom_css_file_path_2'),'')!=0)
{
	ob_start();
	include($params->get('custom_css_file_path_2'));
	$styles = ob_get_contents();
	
	// Remove comments.
	if ($params->get('remove_comments'))
	{
		$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
	}
	
	// Minimize.
	if ($params->get('minimize'))
	{
		$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
		$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
		$styles = trim($styles);		// Trim the string of leading and trailing space.
	}
	
	// Convert short absolute paths to full absolute paths.
	if ($params->get('full_absolute_paths'))
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
if (strcmp($params->get('custom_css_file_path_3'),'')!=0)
{
	ob_start();
	include($params->get('custom_css_file_path_3'));
	$styles = ob_get_contents();
	
	// Remove comments.
	if ($params->get('remove_comments'))
	{
		$styles = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $styles);
	}
	
	// Minimize.
	if ($params->get('minimize'))
	{
		$styles = str_replace(array("\r\n", "\r", "\n", "\t"), '', $styles);
		$styles = preg_replace('/ +/', ' ', $styles);	// Replace multiple spaces with single space.
		$styles = trim($styles);		// Trim the string of leading and trailing space.
	}
	
	// Convert short absolute paths to full absolute paths.
	if ($params->get('full_absolute_paths'))
	{
		$styles = str_replace('url(/', 'url('.JURI::base(), $styles);
		$styles = str_replace("url('/", "url('".JURI::base(), $styles);
		$styles = str_replace('url("/', 'url("'.JURI::base(), $styles);
	}
	
	ob_clean();
	//$document->addStyleDeclaration($styles);
	$document->addCustomTag('<style type="text/css">'.$styles.'</style>');
}
?>