<?php 
/**
 * IceCarosuel Extension for Joomla 1.6 By IceTheme
 * 
 * 
 * @copyright	Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license		GNU General Public License version 2
 * 
 * @Website 	http://www.icetheme.com/Joomla-Extensions/iceaccordion.html
 * @Support 	http://www.icetheme.com/Forums/IceCarosuel/
 *
 */
 

/* no direct access*/
defined('_JEXEC') or die;


jimport('joomla.application.component.model');

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';

jimport('joomla.application.component.model');

JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');

if( !class_exists('IceGroupBase') ){
	require_once( dirname(__FILE__).DS.'libs'.DS.'group_base.php' );
}

abstract class modIceCarousel {
	
	/**
	 * get list articles
	 */
	public static function getList( $params ){
		$list = array();
		if ( $params->get('enable_cache') ) {
			$cache =& JFactory::getCache('mod_ice_carousel');
			$cache->setCaching( true );
			$cache->setLifeTime( $params->get( 'cache_time', 15 ) * 60 );	
			$list = $cache->get( array( 'modIceCarousel' , 'getGroupObject' ), array( $params ) ); 
		} else {
			$list =  self::getGroupObject( $params );
		}
		if($list){
			$grouped = false;
			$article_grouping = $params->get('article_grouping', 'none');
			$article_grouping_direction = $params->get('article_grouping_direction', 'ksort');
			$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
			$item_heading = $params->get('item_heading');
			
			if ($article_grouping !== 'none') {
				$grouped = true;
				switch($article_grouping)
				{
					case 'year':
					case 'month_year':
						$list = self::groupByDate($list, $article_grouping, $article_grouping_direction, $params->get('month_year_format', 'F Y'));
						break;
					case 'author':
					case 'category_title':
						$list = self::groupBy($list, $article_grouping, $article_grouping_direction);
						break;
					default:
						break;
				}
			}
		}
		return $list;
	}
	
	/**
	 * get list articles
	 */
	public static function getGroupObject( $params ){
		$group = $params->get( 'data_source', 'content' );
		$file = dirname(__FILE__).DS.'libs'.DS.trim($group).'.php';	
		if( file_exists($file) ){
			require_once( $file );
			$className = 'IceGroup'.ucfirst($group);
			if( class_exists($className) ){
				$object = new $className( $group );					
				$object->setCurrentPath(  dirname(__FILE__).DS.'libs'.DS.'groups'.DS.strtolower($group).DS );							
			
			}
		}
		if( $object ){
			return $object->getListByParameters( $params );	
		} else {
			return array();
		}
	}
	

	/**
	 * load css - javascript file.
	 * 
	 * @param JParameter $params;
	 * @param JModule $module
	 * @return void.
	 */
	public static function loadMediaFiles( $params, $module, $theme='' ){
		$mainframe = &JFactory::getApplication();
		$enable_bootrap = $params->get("enable_bootrap", 1);
		$document = &JFactory::getDocument();
		$document->addScript(JURI::base()."modules/mod_ice_carousel/assets/jquery.flexslider-min.js");
		JHTML::stylesheet("modules/mod_ice_carousel/assets/flexslider.css");
			
		JHTML::stylesheet( 'modules/'.$module->module.'/assets/style.css' );		
		if( $theme && $theme != -1 ){
			$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'css'.DS.$module->module.'_'.$theme.'.css';
			if( file_exists($tPath) ){
				JHTML::stylesheet( 'templates/'.$mainframe->getTemplate().'/css/'.$module->module.'_'.$theme.'.css');
			} else {
				JHTML::stylesheet('modules/'.$module->module.'/themes/'.$theme.'/assets/style.css');	
			}
		}
	}
	
	/**
	 *
	 */
	public function renderItem( &$row, $params, $layout='_item' ){
		$target = $params->get('open_target','_parent') != 'modalbox'
							? 'target="'.$params->get('open_target','_parent').'"'
							: 'rel="'.$params->get('modal_rel','width:800,height:350').'" class="mb"'; 
							
		$path = dirname(__FILE__).DS.'themes'.DS.$params->get('theme').DS;
		if( file_exists($path.$params->get('group').$layout.'.php') ){
			require( $path.$params->get('group').$layout.'.php' );
			return ;
		}
		require( $path.$layout.'.php' );
	}
	
	/**
	 * load theme
	 */
	public static function getLayoutByTheme( $module, $theme= '', $layout1=""){
		$mainframe = &JFactory::getApplication();
		$layout = 'default';
		if( $theme ) {
			$layout = trim($theme).'_default';
		}
		if( !empty($layout1)){
			$layout = $layout1;
		}
		// Build the template and base path for the layout
		$tPath = JPATH_BASE.DS.'templates'.DS.$mainframe->getTemplate().DS.'html'.DS.$module->module.DS.$layout.'.php';
		$bPath = JPATH_BASE.DS.'modules'.DS.$module->module.DS.'tmpl'.DS.$layout.'.php';

		// If the template has a layout override use it
		if (file_exists($tPath)) {
			return $tPath;
		} elseif( file_exists($bPath) ) {
			return $bPath;
		}
		return JPATH_BASE.DS.'modules'.DS.$module->module.DS.'themes'.DS.$theme.DS.'default.php';
	}
	public static function getPaging( $numb_page = 1, $current_page = 1)
	{
		$str = '<div class="iceaccordion-paging">';
		$str .= '<div class="iceaccordion-loading" style="display: none;"></div>';
		$str .= ' <div class="iceaccordion-prev"><span>'.JText::_("Prev").'</span></div>
        <div class="iceaccordion-next"><span>'.JText::_("Next").'</span></div>';
		$str .= ' <ul class="iceaccordion-numbers">';
		if(!empty($numb_page)){
			for( $i = 1; $i <= $numb_page; $i++){
				if($i == $current_page){
					$str .= '<li class="active">'.$i.'</li>';
				}
				else{
					$str .= '<li class="">'.$i.'</li>';
				}
			}
		}
		$str .='</ul>';
		$str .= '</div>';
		return $str;
	}
	public static function checkIceAjax(){
		$type = JRequest::getVar("type","");
		$module_id = JRequest::getVar("moduleId",0);
		$layout = JRequest::getVar("layout","");
		$page = JRequest::getVar("p", 1);
		$layouts = array("_items","_products");
		if(!empty($module_id) && in_array($layout, $layouts) && $type == "ice_carosuel"){
			return true;
		}
		return false;
	}
	 public static function checkActualCat( $params = array()){
		$source = trim($params->get( 'source_from', 'cat_ids' ) );
		if($source =="actual_cat"){
			$controller = JRequest::getCmd('controller','');
			$task = JRequest::getCmd('task','');
			$product_id = JRequest::getCmd('product_id',0);
			$category_id = JRequest::getCmd('category_id', 0);
			if($controller == "product" && $task == "view" && !empty($product_id)){
				return true;
			}
			else{
				return false;
			}
		}
		return true;
	}
	public static function getAjaxData( $params = array()){
		$string = "";
		$source = trim($params->get( 'source_from', 'cat_ids' ) );
		if($source =="actual_cat"){
			$controller = JRequest::getCmd('controller','');
			$task = JRequest::getCmd('task','');
			$product_id = JRequest::getCmd('product_id',0);
			$category_id = JRequest::getCmd('category_id', 0);
			if($controller == "product" && $task == "view" && !empty($product_id)){
				$string = ',controller:"'.$controller.'", task:"'.$task.'", product_id:'.$product_id.',category_id:'.$category_id;
			}
		}
		return $string;
	}
	public static function groupBy($list, $fieldName, $article_grouping_direction, $fieldNameToKeep = null)
	{
		$grouped = array();

		if (!is_array($list)) {
			if ($list == '') {
				return $grouped;
			}

			$list = array($list);
		}

		foreach($list as $key => $item)
		{
			if (!isset($grouped[$item->$fieldName])) {
				$grouped[$item->$fieldName] = array();
			}

			if (is_null($fieldNameToKeep)) {
				$grouped[$item->$fieldName][$key] = $item;
			}
			else {
				$grouped[$item->$fieldName][$key] = $item->$fieldNameToKeep;
			}

			unset($list[$key]);
		}

		$article_grouping_direction($grouped);

		return $grouped;
	}

	public static function groupByDate($list, $type = 'year', $article_grouping_direction, $month_year_format = 'F Y')
	{
		$grouped = array();

		if (!is_array($list)) {
			if ($list == '') {
				return $grouped;
			}

			$list = array($list);
		}

		foreach($list as $key => $item)
		{
			switch($type)
			{
				case 'month_year':
					$month_year = JString::substr($item->created, 0, 7);

					if (!isset($grouped[$month_year])) {
						$grouped[$month_year] = array();
					}

					$grouped[$month_year][$key] = $item;
					break;

				case 'year':
				default:
					$year = JString::substr($item->created, 0, 4);

					if (!isset($grouped[$year])) {
						$grouped[$year] = array();
					}

					$grouped[$year][$key] = $item;
					break;
			}

			unset($list[$key]);
		}

		$article_grouping_direction($grouped);

		if ($type === 'month_year') {
			foreach($grouped as $group => $items)
			{
				$date = new JDate($group);
				$formatted_group = $date->format($month_year_format);
				$grouped[$formatted_group] = $items;
				unset($grouped[$group]);
			}
		}

		return $grouped;
	}
}
?>
