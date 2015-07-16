<?php
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
/**
 * $ModDesc
 * 
 * @version		$Id: helper.php $Revision
 * @package		modules
 * @subpackage	$Subpackage
 * @copyright	Copyright (C) May 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website 	htt://landofcoder.com
 * @license		GNU General Public License version 2
 */
 jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models', 'ContentModel');
if( !class_exists('IceGroupContent') ){ 
	class IceGroupContent extends IceGroupBase{
		
		/**
		 * @var string $__name;
		 *
		 * @access private
		 */
		var $__name = 'content';
		
		/**
		 * override method: get list image from articles.
		 */
		function getListByParameters( &$params ){ 
			return self::getList( $params );
		}
			
	public static function getList(&$params)
	{
		  $isThumb       = $params->get( 'auto_renderthumb',1);
		  $alway_render_thumb       = $params->get( 'alway_render_thumb',0);
		  $alway_render_thumb 		 = ($alway_render_thumb == 1)?true:false;
		  $image_quanlity = $params->get('image_quanlity', 100);
		  $imageHeight   = (int)$params->get( 'main_height', 300 ) ;
		  $imageWidth    = (int)$params->get( 'main_width', 900 ) ;
		  $isStripedTags = $params->get( 'strip_tags', 0 );
		  
		// Set application parameters in model
		$app = JFactory::getApplication();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();
		$model->setState('params', $appParams);

		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('limit_items', 5));

		$model->setState('filter.published', 1);

		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.introtext, a.checked_out, a.checked_out_time, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by, a.publish_up, a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured' );

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$source_from = $params->get("source_from", "catid");
		switch($source_from){
			case 'article_id':
				$tmp = $params->get("article_id", "");
				$article_ids = explode(",", $tmp);
				$model->setState('filter.article_id', $article_ids);
			break;			
			case 'catid':
			default:
				$model->setState('filter.category_id', $params->get('catid', array()));
			break;
		}

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());
		// Set ordering
		$ordering = $params->get('ordering', 'a.ordering__ASC');
		$tmp = explode("__",$ordering);
		$tmp[1] = isset($tmp[1])?$tmp[1]:"DESC";
		$model->setState('list.ordering', $tmp[0]);
		if (trim($tmp[0]) == 'rand()') {
			$model->setState('list.direction', '');
		} else {
			$model->setState('list.direction', $tmp[1]);
		}		
		
		$items = $model->getItems();

		// Display options
		$introtext_limit = $params->get( 'description_max_chars', 100 );
		$title_max_chars = $params->get('title_max_chars', 100);
		// Find current Article ID if on an article page
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');

		if ($option === 'com_content' && $view === 'article') {
			$active_article_id = JRequest::getInt('id');
		}
		else {
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;
			if ($access || in_array($item->access, $authorised)) {
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			 else {
				// Angie Fixed Routing
				$app	= JFactory::getApplication();
				$menu	= $app->getMenu();
				$menuitems	= $menu->getItems('link', 'index.php?option=com_users&view=login');
				if(isset($menuitems[0])) {
					$Itemid = $menuitems[0]->id;
				} else if (JRequest::getInt('Itemid') > 0) { //use Itemid from requesting page only if there is no existing menu
					$Itemid = JRequest::getInt('Itemid');
				}

				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
				}

			// Used for styling the active article
			$item->active = $item->id == $active_article_id ? 'active' : '';
			if ($item->catid) {
				$item->displayCategoryLink = JRoute::_(ContentHelperRoute::getCategoryRoute($item->catid));
				$item->displayCategoryTitle = '<a href="'.$item->displayCategoryLink.'">'.$item->category_title.'</a>';
			}
			else {
				$item->displayCategoryTitle = $item->category_title;
			}

			$item->displayHits = $item->hits ;
			$item->displayAuthorName = $item->author ;
			$item->text = $item->fulltext;
			$item->introtext = JHtml::_('content.prepare', $item->introtext);
			self::parseImages( $item );
			if( $item->mainImage &&  $image=self::renderThumb($item->mainImage, $imageWidth, $imageHeight, $item->title, $isThumb, $image_quanlity, false, false, $alway_render_thumb ) ){
				$item->mainImage = $image;
			}
			$item->introtext = self::_cleanIntrotext($item->introtext);
			
			// truncate or not 
			if ($params->get('istruncate') == 0) :
			$item->displayIntrotext = $item->introtext;
			$item->title = $item->title; 
			else:
			$item->displayIntrotext = self::truncate($item->introtext, $introtext_limit);
			$item->title = self::truncate($item->title, $title_max_chars); 
			endif;
			
			// added Angie show_unauthorizid
			$item->displayReadmore = $item->alternative_readmore;
			
		}

		return $items;
	}

	public static function _cleanIntrotext($introtext)
	{
		$introtext = str_replace('<p>', ' ', $introtext);
		$introtext = str_replace('</p>', ' ', $introtext);
		$introtext = strip_tags($introtext, '<a><em><strong>');

		$introtext = trim($introtext);

		return $introtext;
	}

	/**
	* This is a better truncate implementation than what we
	* currently have available in the library. In particular,
	* on index.php/Banners/Banners/site-map.html JHtml's truncate
	* method would only return "Article...". This implementation
	* was taken directly from the Stack Overflow thread referenced
	* below. It was then modified to return a string rather than
	* print out the output and made to use the relevant JString
	* methods.
	*
	* @link http://stackoverflow.com/questions/1193500/php-truncate-html-ignoring-tags
	* @param mixed $html
	* @param mixed $maxLength
	*/
	public static function truncate($html, $maxLength = 0)
	{
		$printedLength = 0;
		$position = 0;
		$tags = array();

		$output = '';

		if (empty($html)) {
			return $output;
		}

		while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position))
		{
			list($tag, $tagPosition) = $match[0];

			// Print text leading up to the tag.
			$str = JString::substr($html, $position, $tagPosition - $position);
			if ($printedLength + JString::strlen($str) > $maxLength) {
				$output .= JString::substr($str, 0, $maxLength - $printedLength);
				$printedLength = $maxLength;
				break;
			}

			$output .= $str;
			$lastCharacterIsOpenBracket = (JString::substr($output, -1, 1) === '<');

			if ($lastCharacterIsOpenBracket) {
				$output = JString::substr($output, 0, JString::strlen($output) - 1);
			}

			$printedLength += JString::strlen($str);

			if ($tag[0] == '&') {
				// Handle the entity.
				$output .= $tag;
				$printedLength++;
			}
			else {
				// Handle the tag.
				$tagName = $match[1][0];

				if ($tag[1] == '/') {
					// This is a closing tag.
					$openingTag = array_pop($tags);

					$output .= $tag;
				}
				else if ($tag[JString::strlen($tag) - 2] == '/') {
					// Self-closing tag.
					$output .= $tag;
				}
				else {
					// Opening tag.
					$output .= $tag;
					$tags[] = $tagName;
				}
			}

			// Continue after the tag.
			if ($lastCharacterIsOpenBracket) {
				$position = ($tagPosition - 1) + JString::strlen($tag);
			}
			else {
				$position = $tagPosition + JString::strlen($tag);
			}

		}

		// Print any remaining text.
		if ($printedLength < $maxLength && $position < JString::strlen($html)) {
			$output .= JString::substr($html, $position, $maxLength - $printedLength);
		}

		// Close any open tags.
		while (!empty($tags))
		{
			$output .= sprintf('</%s>', array_pop($tags));
		}

		$length = JString::strlen($output);
		$lastChar = JString::substr($output, ($length - 1), 1);
		$characterNumber = ord($lastChar);

		if ($characterNumber === 194) {
			$output = JString::substr($output, 0, JString::strlen($output) - 1);
		}

		$output = JString::rtrim($output);

		return $output.'&hellip;';
	}
	}
}
?>
