<?php
/*----------------------------------------------------------------------------------------------
| Kiss General Component Helper Routines							Version	2.5.00
|-----------------------------------------------------------------------------------------------
| Author: Heiner Klostermann (DocChicago) - KISS Software Germany				
| Copyright (c) 2010 by KISS Software, 59227 Ahlen (NRW), Germany				
|-----------------------------------------------------------------------------------------------
| License GNU/GPL 					http://www.gnu.org/copyleft/gpl.html
|-----------------------------------------------------------------------------------------------
|												
| This component is free software, distributed under the GNU / GPL License. You may use,	
| copy and redistribute it free of charge as long as it complies with the license terms.	
|												
| 
| For help and suggestions please visit our support forum at www.kiss-software.de
|
|-----------------------------------------------------------------------------------------------
| created 2011-11-01							updated	2014-08-29
|-----------------------------------------------------------------------------------------------*/
jimport('joomla.application.component.controller');
jimport( 'joomla.filesystem.folder' ); 
jimport( 'joomla.filesystem.file' );

class KissGeneralComponentHelper
{	
	function CategoryTreeOption($data, $tree, $id=0, $text='', $currentId) {		

		foreach ($data as $key) {	
			$show_text =  $text . $key->text;		
			if ($key->parentid == $id && $currentId != $id && $currentId != $key->value) {
				$tree[$key->value] 			= new JObject();
				$tree[$key->value]->text 	= $show_text;
				$tree[$key->value]->value 	= $key->value;
				$tree = self::CategoryTreeOption($data, $tree, $key->value, $show_text . " - ", $currentId );	
			}	
		}
		return($tree);
	}
	
  /*------------------------------------------------------------------
	hasAccess
	checks whether a user has access to a certain action (to check)
	@params:	$user_id	- the user number (-1 if current user)
				$checkright - the access right to be checked against
				$match		- [boolean] - 1 if exact match
	Copyright (c) 2013 by KISS Software
  -------------------------------------------------------------------*/
	public static function hasAccess($user_id = 0, $checkright = Null, $match = 0) {
		$user_aid 		= self::get_user_group($user_id);
		$user_gid 		= (strpos($user_aid, ",") === false) ? array('0','1',$user_aid) : explode(",", $user_aid);
	
		switch ($match) {
		case 0:
		foreach ($user_gid as $check) {	
			if ($check >= $checkright) {		
				return true;
				}
			}
			return false;
		break;
		case 1:
		if (in_array($checkright, $user_gid)) {
			return true;
			} else {
			return false;
			}
		break;
		}
	}

  /*------------------------------------------------------------------
	get_user_group
	gets the group(s) a user belongs to
	Copyright (c) 2013 by KISS Software
  -------------------------------------------------------------------*/
	public static function get_user_group ($user_id) {
		// Detect user group
		$db = JFactory::getDBO();
		$query = "SELECT * FROM ".$db->quoteName(KISS_COMPONENT_JPFX."user_usergroup_map")." WHERE user_id = ".$user_id." ORDER BY group_id";
		$db->setQuery($query);
		$user_gid = '';
		if($usergroup = $db->loadObjectList()) {
			foreach ($usergroup as $group) {
					$user_gid .= $group->group_id.",";
				}
			} else {
			// No usergroup defined, set to public
			$user_gid = "1,";
			}
		return substr($user_gid, 0, -1);	
		}

	/**
	 * Method to display multiple select box with users
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $nouser Select no user if 1
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $reg Only registered users
	 * @param int $mult Multiple entries can be selected	 
	 * @return array of id
	 */
	
	function usersList( $name, $id, $active, $nouser = 0, $javascript = NULL, $order = 'name', $reg = 1, $mult = 0, $style = '' ) {
		
		$activeArray = $active;
		if ($active != '') {
			$activeArray = explode(',',$active);
		}
		
		$db		= JFactory::getDBO();
		$and 	= '';
		if ($reg) {
			// does not include registered users in the list
			$and = ' AND m.group_id != 2';
		}

		if (self::field_exists('users', 'firstname')) {
		    $query = "SELECT u.id AS value, CONCAT_WS(' ', u.firstname, u.name) AS text";
		    } else {
		    $query = "SELECT u.id AS value, u.name AS text";
		    }
		$query .= ' FROM '.$db->quoteName(KISS_COMPONENT_JPFX.'users').' AS u'
		. ' JOIN '.$db->quoteName(KISS_COMPONENT_JPFX.'user_usergroup_map').' AS m ON m.user_id = u.id'
		. ' WHERE u.block = 0'
		. $and
		. ' GROUP BY u.id'
		. ' ORDER BY '. $order;
		
		$db->setQuery( $query );
		if ( $nouser ) {
			
			// Access rights (Default open for all)
			// Upload and Delete rights (Default closed for all)
			switch ($name) {
				case 'jform[accessuserid][]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'KISS_CONFIG_ALL_REGISTERED' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'KISS_CONFIG_NOBODY' );
				break;
				
				Default:
					$idInput1 	= -2;
					$idText1	= JText::_( 'KISS_CONFIG_NOBODY' );
				break;
			}
			
			$users[] = JHTML::_('select.option',  $idInput1, '- '. $idText1 .' -' );
//			$users[] = JHTML::_('select.option',  $idInput2, '- '. $idText2 .' -' );
			
			$users = array_merge( $users, $db->loadObjectList() );
		} else {
			$users = $db->loadObjectList();
		}
		$multiple = ($mult == 1) ? ' multiple="multiple" ' : ' ';
		$users = JHTML::_('select.genericlist', $users, $name, 'class="inputbox" size="4"' . $style . $multiple. $javascript, 'value', 'text', $activeArray, $id );

		return $users;
	}
	
	
	/**
	 * Method to display a select box
	 * @param string $name Name (id, name parameters)
	 * @param array $active Array of items which will be selected
	 * @param int $noselect Select no item
	 * @param string $javascript Add javascript to the select box
	 * @param string $order Ordering of items
	 * @param int $table The name of the table to search in
	 * @param string $select_sql The select SQL database query string
	 * @param int $size The number of items simultaneously being shown in the select box	 
	 * @param bool $multiple If 1, more than one item can be selected in the select box	 	 
	 * @param bool $meselect If 1, own entry is not shown in the box		 
	 * @return array of id
	 */
	
	function tableList( $name, $id, $active, $noselect = 0, $javascript = NULL, $order = 'name', $table = Null, $select_sql, $where_sql = Null, $size = 1, $multiple = 0, $meselect = 0, $style = '', $encrypted = 0 ) {
		$curr_id	= JRequest::getVar( 'id', '', '', 'int' );		
		$activeArray = $active;
		if ($active != '') {
			$activeArray = explode(',',$active);
		}
		
		$db		= JFactory::getDBO();

		$query = 'SELECT '.$select_sql
		. ' FROM '.KISS_COMPONENT_JPFX.$table;
		if (isset($where_sql)) {
		    $query .= ' WHERE '. $where_sql;
		    } else {
			$query .= ' WHERE id > 0';
			}
		    
		if ($meselect == 1) {
			$query .= ' AND id != '.$curr_id;
			}
		$query .= ' ORDER BY '. $order;	
	
		$db->setQuery( $query );
		if ( $noselect == 1 ) {	
			// Access rights (Default open for all)
			// Upload and Delete rights (Default closed for all)
			
			switch ($name) {				
				Default:
					$idInput1 	= 0;
					$idText1	= JText::_('KISS_GENERAL_NONE');
				break;

				// Special field values 
				case 'jform[accessuserid]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'KISS_CONFIG_ALL_REGISTERED_USERS' );
					$idInput2 	= -2;
					$idText2	= JText::_( 'KISS_CONFIG_NOBODY' );
				break;	

				case 'jform[fee]':
					$idInput1 	= -1;
					$idText1	= JText::_( 'KISS_CONFIG_ALL' );
					$idInput2 	= 0;
					$idText2	= JText::_( 'KISS_GENERAL_NONE' );
				break;	
			}
			
			if (isset($idText1)) {
				$idText1 = ($encrypted) ? self::encrypted('- '. $idText1 .' -') : '- '. $idText1 .' -';
				}
			if (isset($idText2)) {
				$idText2 = ($encrypted) ? self::encrypted('- '. $idText2 .' -') : '- '. $idText2 .' -';
				}
			$items[] = JHTML::_('select.option',  $idInput1, $idText1 );

			if (isset($idInput2)) {
				$items[] = JHTML::_('select.option',  $idInput2, $idText2 );
				}
			if ($items2 = $db->loadObjectList()) {
				$items = array_merge( $items, $items2 );
				}
		} else {
			if ($items2 = $db->loadObjectList()) {
				$items = $items2;
				}
		}

		// Convert the ressource strings into language strings
		foreach($items as $items_lang) {
			$items_lang->text = ($encrypted) ? JText::_(self::decrypted($items_lang->text)): JText::_($items_lang->text);
			}

		if ($multiple) {
			$items = JHTML::_('select.genericlist', $items, $name, 'class="inputbox" '.$style.' size="'.$size.'" multiple="multiple"'. $javascript, 'value', 'text', $activeArray, $id );
			} else {
			$items = JHTML::_('select.genericlist', $items, $name, 'class="inputbox" '.$style.' size="'.$size.'" ' . $javascript, 'value', 'text', $activeArray, $id );		
			}
		return $items;
	}

	function getAliasName($name) {
		
		$paramsC		= JComponentHelper::getParams( KISS_COMPONENT_JDIR );
		$alias_iconv	= $paramsC->get( 'alias_iconv', 0 );
		
		$iconv = 0;
		if ($alias_iconv == 1) {
			if (function_exists('iconv')) {
				$name = preg_replace('~[^\\pL0-9_.]+~u', '-', $name);
				$name = trim($name, "-");
				$name = iconv("utf-8", "us-ascii//TRANSLIT", $name);
				$name = strtolower($name);
				$name = preg_replace('~[^-a-z0-9_.]+~', '', $name);
				$iconv = 1;
			} else {
				$iconv = 0;
			}
		}
		
		if ($iconv == 0) {
			$name = JFilterOutput::stringURLSafe($name);
		}
		
		if(trim(str_replace('-','',$name)) == '') {
			$datenow	= JFactory::getDate();
			$name 		= $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}
		return $name;
	}
		
	function filterCategory($query, $active = NULL, $frontend = NULL, $onChange = TRUE, $fullTree = NULL, $name = 'cid') {
		$db	=  JFactory::getDBO();
       /*build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.parent_id as parentid'
		. ' FROM '.KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX.'_categs AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		*/
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		// TODO - check for other views than category edit
		$view 	= JRequest::getVar( 'view' );
		$catId	= -1;
		if ($view == KISS_COMPONENT_FILE.'cat') {
			$id 	= $this->form->getValue('id'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}
		if ($view == KISS_COMPONENT_FILE.'item') {
			$id 	= $this->form->getValue('cid'); // id of current category
			if ((int)$id > 0) {
				$catId = $id;
			}
		}
		$onchng = ($onChange == TRUE) ? ' onchange="this.form.submit()"' : '';
		$tree = array();
		$text = '';
		$tree = self::CategoryTreeOption($data, $tree, 0, $text, $catId);
		
		//if ($required == TRUE) {
		
		//} else {
		
			array_unshift($tree, JHTML::_('select.option', '', '- '.JText::_('KISS_GENERAL_SELECT').' -', 'value', 'text'));
		//}
		return JHTML::_('select.genericlist',  $tree,  $name, 'class="inputbox"'.$onchng, 'value', 'text', $active, $name );
	}

	function strTrimAll($input) {
		$output	= '';
	    $input	= trim($input);
	    for($i=0;$i<strlen($input);$i++) {
	        if(substr($input, $i, 1) != " ") {
	            $output .= trim(substr($input, $i, 1));
	        } else {
	            $output .= " ";
	        }
	    }
	    return $output;
	}
	
	function getTitleFromFilenameWithoutExt (&$filename) {
	
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		$string = false;
		$string = preg_match( "/\./i", $folder_array[$last_array_value] );
		if ($string) {
			return self::removeExtension($folder_array[$last_array_value]);
		} else {
			return $folder_array[$last_array_value];
		}
	}
	
	function removeExtension($file_name) {
		return substr($file_name, 0, strrpos( $file_name, '.' ));
	}
	
	function getExtension( $file_name ) {
		return strtolower( substr( strrchr( $file_name, "." ), 1 ) );
	}
	
	
	function canPlay( $fileName ) {
		$fileExt 	= self::getExtension($fileName);
		
		switch($fileExt) {
			case 'mp3':
			case 'mp4':
			case 'flv':
			//case 'mov':
			//case 'wmv':
			return true;
			break;
			
			default:
			return false;
			break;
		
		}
	
		return false;
	}
	
	function getPathSet( $item = '') {
	$user = JFactory::getUser();
	$userid = $user->get('id');	
		if ($item == 'icon' || $item == 'iconspec1' || $item == 'iconspec2') {
			$path['orig_abs_ds'] 				= JPATH_ROOT . DS . 'images' . DS . KISS_COMPONENT_FILE . DS ;
			$path['orig_abs'] 					= JPATH_ROOT . DS . 'images' . DS . KISS_COMPONENT_FILE ;
			$path['orig_abs_user_upload'] 		= $path['orig_abs'] . DS . 'users' . DS . $userid ;
			$path['orig_rel_ds'] 				= '..'. DS .'images' . DS . KISS_COMPONENT_FILE . DS;
		} else {
			// File
			$paramsC							= JComponentHelper::getParams( KISS_COMPONENT_JDIR );
			
			// Absolute path which can be outside public_html
			$absolutePath						= $paramsC->get( 'absolute_path', '' );
			if ($absolutePath != '') {
				$downloadFolder 				= str_replace('/', DS, JPath::clean($absolutePath));
				$path['orig_abs_ds'] 			= $absolutePath . DS ;
				$path['orig_abs'] 				= $absolutePath ;
				$path['orig_abs_user_upload'] 	= $path['orig_abs'] . DS . 'users'  . DS . $userid;
				//$downloadFolderRel 			= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '';

				} else {

				$downloadFolder					= $paramsC->get( 'upl_folder', 'media' . DS . KISS_COMPONENT_JDIR . DS . 'images' );
				$downloadFolder 				= str_replace('/', DS, JPath::clean($downloadFolder));
				$path['orig_abs_ds'] 			= JPATH_ROOT . DS . $downloadFolder . DS ;
				$path['orig_abs'] 				= JPATH_ROOT . DS . $downloadFolder ;
				$path['orig_abs_user_upload'] 	= $path['orig_abs'] . DS . 'users'  . DS . $userid;
				
				$downloadFolderRel 				= str_replace(DS, '/', JPath::clean($downloadFolder));
				$path['orig_rel_ds'] 			= '..' . DS . $downloadFolderRel . DS;
			}
		}
		return $path;
	}
	
	
	function getFormfields($extension = 0) {
		$db = JFactory::getDBO();	
		$ext = ($extension) ? "WHERE ".$db->quoteName('extensiongroup')." = ".$db->quote($extension) : "";		
		$query = "SELECT * FROM ".$db->quoteName(KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_formfields") . " $ext ORDER BY tab ASC, fieldset ASC, ordering ASC";
		$db->setQuery($query);
		$flds = $db->loadObjectList();		
		return $flds;	
	}

	function getFileSize($filename, $readable = 1) {
		
		$path			= &self::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . DS . $filename);
		
		if ($readable == 1) {
			return self::getFileSizeReadable(filesize($fileNameAbs));
		} else {
			return filesize($fileNameAbs);
		}
	}
	
	/*
	 * http://aidanlister.com/repos/v/function.size_readable.php
	 */
	function getFileSizeReadable ($size, $retstring = null, $onlyMB = false) {
	
		if ($onlyMB) {
			$sizes = array('B', 'kB', 'MB');
		} else {
			$sizes = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        }
		

		if ($retstring === null) { $retstring = '%01.2f %s'; }
        $lastsizestring = end($sizes);
		
        foreach ($sizes as $sizestring) {
                if ($size < 1024) { break; }
                if ($sizestring != $lastsizestring) { $size /= 1024; }
        }
		
        if ($sizestring == $sizes[0]) { $retstring = '%01d %s'; } // Bytes aren't normally fractional
        return sprintf($retstring, $size, $sizestring);
	}
	
	
	function getFileTime($filename, $function, $format = "d. M Y") {
		
		$path			= self::getPathSet();
		$fileNameAbs	= JPath::clean($path['orig_abs'] . DS . $filename);
		if (JFile::exists($fileNameAbs)) {
			switch($function) {
				case 2:
					$fileTime = filectime($fileNameAbs);
				break;
				case 3:
					$fileTime = fileatime($fileNameAbs);
				break;
				case 1:
				default:
					$fileTime = filemtime($fileNameAbs);
				break;
			}
			
			$fileTime = JHTML::Date($fileTime, $format);
		} else {
			$fileTime = '';
		}
		return $fileTime;
	}

	function arraySort() {
		//get args of the function
		$args = func_get_args();
		$c = count($args);
		if ($c < 2) {
			return false;
		}
		//get the array to sort
		$array = array_splice($args, 0, 1);
		$array = $array[0];
		//sort with an anoymous function using args
		usort($array, function($a, $b) use($args) {

			$i = 0;
			$c = count($args);
			$cmp = 0;
			while($cmp == 0 && $i < $c)
			{
				$cmp = strcmp($a[ $args[ $i ] ], $b[ $args[ $i ] ]);
				$i++;
			}

			return $cmp;

		});

		return $array;

	}

	
	function getTitleFromFilenameWithExt (&$filename) {
		$folder_array		= explode('/', $filename);//Explode the filename (folder and file name)
		$count_array		= count($folder_array);//Count this array
		$last_array_value 	= $count_array - 1;//The last array value is (Count array - 1)	
		
		return $folder_array[$last_array_value];
	}

	
	function getMimeType($extension, $params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$returnMime = '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$kissDownload	= $matches[0][$i][0];
			preg_match($regex_one,$kissDownload,$kissDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $kissDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}

			// Return mime if extension call it
			if ($extension == $values[0]) {
				$returnMime = $values[1];
			}
		}

		if ($returnMime != '') {
			return $returnMime;
		} else {
			return "KISSErrorNoMimeFound";
		}
	}
	
	function getMimeTypeString($params) {
		
		$regex_one		= '/({\s*)(.*?)(})/si';
		$regex_all		= '/{\s*.*?}/si';
		$matches 		= array();
		$count_matches	= preg_match_all($regex_all,$params,$matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

		$extString 	= '';
		$mimeString	= '';
		
		for($i = 0; $i < $count_matches; $i++) {
			
			$kissDownload	= $matches[0][$i][0];
			preg_match($regex_one,$kissDownload,$kissDownloadParts);
			$values_replace = array ("/^'/", "/'$/", "/^&#39;/", "/&#39;$/", "/<br \/>/");
			$values = explode("=", $kissDownloadParts[2], 2);	
			
			foreach ($values_replace as $key2 => $values2) {
				$values = preg_replace($values2, '', $values);
			}
				
			// Create strings
			$extString .= $values[0];
			$mimeString .= $values[1];
			
			$j = $i + 1;
			if ($j < $count_matches) {
				$extString .=',';
				$mimeString .=',';
			}
		}
		
		$string 		= array();
		$string['mime']	= $mimeString;
		$string['ext']	= $extString;
		
		return $string;
	}
	
	
	function getDefaultAllowedMimeTypesDownload() {
		return '{hqx=application/mac-binhex40}{cpt=application/mac-compactpro}{csv=text/x-comma-separated-values}{bin=application/macbinary}{dms=application/octet-stream}{lha=application/octet-stream}{lzh=application/octet-stream}{exe=application/octet-stream}{class=application/octet-stream}{psd=application/x-photoshop}{so=application/octet-stream}{sea=application/octet-stream}{dll=application/octet-stream}{oda=application/oda}{pdf=application/pdf}{ai=application/postscript}{eps=application/postscript}{ps=application/postscript}{smi=application/smil}{smil=application/smil}{mif=application/vnd.mif}{xls=application/vnd.ms-excel}{ppt=application/powerpoint}{wbxml=application/wbxml}{wmlc=application/wmlc}{dcr=application/x-director}{dir=application/x-director}{dxr=application/x-director}{dvi=application/x-dvi}{gtar=application/x-gtar}{gz=application/x-gzip}{php=application/x-httpd-php}{php4=application/x-httpd-php}{php3=application/x-httpd-php}{phtml=application/x-httpd-php}{phps=application/x-httpd-php-source}{js=application/x-javascript}{swf=application/x-shockwave-flash}{sit=application/x-stuffit}{tar=application/x-tar}{tgz=application/x-tar}{xhtml=application/xhtml+xml}{xht=application/xhtml+xml}{zip=application/x-zip}{mid=audio/midi}{midi=audio/midi}{mpga=audio/mpeg}{mp2=audio/mpeg}{mp3=audio/mpeg}{aif=audio/x-aiff}{aiff=audio/x-aiff}{aifc=audio/x-aiff}{ram=audio/x-pn-realaudio}{rm=audio/x-pn-realaudio}{rpm=audio/x-pn-realaudio-plugin}{ra=audio/x-realaudio}{rv=video/vnd.rn-realvideo}{wav=audio/x-wav}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{css=text/css}{html=text/html}{htm=text/html}{shtml=text/html}{txt=text/plain}{text=text/plain}{log=text/plain}{rtx=text/richtext}{rtf=text/rtf}{xml=text/xml}{xsl=text/xml}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{movie=video/x-sgi-movie}{doc=application/msword}{xl=application/excel}{eml=message/rfc822}{pptx=application/vnd.openxmlformats-officedocument.presentationml.presentation}{xlsx=application/vnd.openxmlformats-officedocument.spreadsheetml.sheet}{docx=application/vnd.openxmlformats-officedocument.wordprocessingml.document}{rar=application/x-rar-compressed}{odf=application/x-vnd.oasis.opendocument.formula}';
	}
	
	function getDefaultAllowedMimeTypesUpload() {
		return '{pdf=application/pdf}{ppt=application/powerpoint}{gz=application/x-gzip}{tar=application/x-tar}{tgz=application/x-tar}{zip=application/x-zip}{bmp=image/bmp}{gif=image/gif}{jpeg=image/jpeg}{jpg=image/jpeg}{jpe=image/jpeg}{png=image/png}{tiff=image/tiff}{tif=image/tiff}{txt=text/plain}{mpeg=video/mpeg}{mpg=video/mpeg}{mpe=video/mpeg}{qt=video/quicktime}{mov=video/quicktime}{avi=video/x-msvideo}{flv=video/x-flv}{doc=application/msword}{mp2=audio/mpeg}{mp3=audio/mpeg}';
	}
	
	function getHTMLTagsUpload() {
		return array('abbr','acronym','address','applet','area','audioscope','base','basefont','bdo','bgsound','big','blackface','blink','blockquote','body','bq','br','button','caption','center','cite','code','col','colgroup','comment','custom','dd','del','dfn','dir','div','dl','dt','em','embed','fieldset','fn','font','form','frame','frameset','h1','h2','h3','h4','h5','h6','head','hr','html','iframe','ilayer','img','input','ins','isindex','keygen','kbd','label','layer','legend','li','limittext','link','listing','map','marquee','menu','meta','multicol','nobr','noembed','noframes','noscript','nosmartquotes','object','ol','optgroup','option','param','plaintext','pre','rt','ruby','s','samp','script','select','server','shadow','sidebar','small','spacer','span','strike','strong','style','sub','sup','table','tbody','td','textarea','tfoot','th','thead','title','tr','tt','ul','var','wbr','xml','xmp','!DOCTYPE', '!--');
	}
	
	
	function displayNewIcon ($date, $time = 0) {
		
		if ($time == 0) {
			return '';
		}
		$params 		= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$dateformat 		= (string) $params->get('dateformat');
		
		$dateAdded 		= strtotime($date, time());
		$dateToday 		= time();
		$dateExists 		= $dateToday - $dateAdded;
		$dateNew		= $time * 24 * 60 * 60;
		$url 			= JURI::ROOT().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-16-newitem.png';		
		$alt 			= JText::_('KISS_GENERAL_NEW');
		$width		 	= '16px';
		$title			= sprintf(JText::_('KISS_CONFIG_MARKASNEW_TITLE'), date($dateformat, $dateAdded));

		if ($dateExists < $dateNew) {
			return "<img src=\"$url\" alt=\"$alt\" title=\"$title\" width=\"$width\" />";;
		} else {
			return '';
		}
	
	}
	
	function displayHotIcon ($hits, $requiredHits = 0) {
		
		if ($requiredHits == 0) {
			return '';
		}
		$params 		= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$thousand_sep 		= $params->get('sep_thousand');
		$decimal_sep 		= $params->get('sep_decimal');
		$decimal_cyp 		= 0;		
		$url 			= JURI::ROOT().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-16-popular.png';		
		$alt 			= 'Hot!';
		$width 			= '16px';
		$title			= sprintf(JText::_('KISS_CONFIG_MARKASHOT_TITLE'), number_format($hits, $decimal_cyp, $decimal_sep, $thousand_sep));
		
		if ($requiredHits <= $hits) {
			return "<img src=\"$url\" alt=\"$alt\" title=\"$title\" width=\"$width\" />";;
		} else {
			return '';
		}
	
	}
	
	function displayRssIcon($item, $what = 'category') {
		$view 			= JRequest::getVar( 'view', '', '', 'string');	
		$params			= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$url 			= JURI::ROOT().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-16-rss.png';
		$user			= JFactory::getUser();
		$pre 			= self::getPrelims();
		
		if (!$user->get('id') || !$pre->l4) {
			return '';
			}
		switch ($what) {
			case 'category':
			default:
			$alt		= JText::_('KISS_GENERAL_NEWSFEED_CATEGORY_DESC');			
			if ($params->get('access_rss') == 3 || $params->get('access_rss') == 1) {			
				$feedlink = '<a href="'.JRoute::_('index.php?option=' .KISS_COMPONENT_JDIR . '&amp;view='.$view.'&amp;cid=' . $item->id . '&amp;uid=0&amp;controller=kissgeneral&amp;task=feed').'"><img src="'.$url.'" border="0" title="'.$alt.'" /></a>';
				} else {
				$feedlink = '';
				}
			break;
			case 'user':
			$alt		= JText::_('KISS_GENERAL_NEWSFEED_USER_DESC');			
			if ($params->get('access_rss') == 3 || $params->get('access_rss') == 2) {			
				$feedlink = '<a href="'.JRoute::_('index.php?option=' . KISS_COMPONENT_JDIR . '&amp;view='.$view.'&amp;cid=0&amp;uid='.$item->uid.'&amp;iid='.$item->id.'&amp;controller=kissgeneral&amp;task=feed').'"><img src="'.$url.'" border="0" title="'.$alt.'" /></a>';
				} else {
				$feedlink = '';
				}
			break;
		}
		return $feedlink;
	}
	
	function displayIcon ($img = Null, $alt='', $title = '', $action = '', $size='16px') {
		
		$url 			= JURI::ROOT().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.$img;		
		$onclick		= (strlen($action) > 0) ? 'onclick="'.$action.'"' : '';
		$width			= (strlen($size) > 0) ? 'width="'.$size.'"': '';
		if (isset($img)) {
			return "<img src=\"$url\" alt=\"$alt\" title=\"$title\" $width $onclick />";
		} else {
			return '';
		}
	
	}	
	public function toArray($value = FALSE) {
		if ($value == FALSE) {
			return array(0 => 0);
		} else if (empty($value)) {
			return array(0 => 0);
		} else if (is_array($value)) {
			return $value;
		} else {
			return array(0 => $value);
		}
	
	}
	
	function approved( &$row, $i, $imgY = 'tick.png', $imgX = 'publish_x.png', $prefix='' ) {
		$img 	= $row->approved ? $imgY : $imgX;
		$task 	= $row->approved ? 'disapprove' : 'approve';
		$alt 	= $row->approved ? JText::_( 'KISS_GENERAL_APPROVED' ) : JText::_( 'KISS_GENERAL_NOT_APPROVED' );
		$action = $row->approved ? JText::_( 'KISS_GENERAL_DISAPPROVE_ITEM' ) : JText::_( 'KISS_GENERAL_APPROVE_ITEM' );

		$href = '
		<a href="javascript:void(0);" onclick="return listItemTask(\'cb'. $i .'\',\''. $prefix.$task .'\')" title="'. $action .'">
		<img src="images/'. $img .'" border="0" alt="'. $alt .'" /></a>'
		;

		return $href;
	}
	
	
	function isURLAddress($url) {
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}

	/*------------------------------------------------------------------
	selectarray:
	Returns an array with all possible selectitems
	Copyright (c) 2010 KISS-Software.de
	-------------------------------------------------------------------*/
	function selectarray($name = NULL, $pub = 0)
	{
		$arr=array();
		$db	= JFactory::getDBO();
		$filter_name = (isset($name)) ? " WHERE name = '$name'" :"";
		$published = ($pub == 1) ? " AND published = '1'" : "";
		$filter = $filter_name.$published;
		$query = "SELECT * FROM ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_selectors$filter ORDER by name, sid";
		$db->setQuery($query);
		$arr = array_merge($arr, $db->loadObjectList());
		return $arr;
	}

	/*------------------------------------------------------------------
	Phonetic
	Changes a string to a phonetic expression (German language only)
	Copyright (c) 2010 KISS-Software.de
	-------------------------------------------------------------------*/
	function phonetic($word){
   
  	/**
  	* @param  string  $word string to be analyzed
  	* @return string  $value represents the German phonetics value
  	* @access public
  	*/
	//prepare for processing
	$word=trim(strtolower($word));

	//avoid complications if $word is empty
	if (strlen($word) < 1) {
	    return;
	    }
	
	$substitution=array(
			"è"=>"e",
			"ë"=>"e",			
			"ä"=>"e",
            "äu"=>"eu",
			"ö"=>"oe",
			"ü"=>"ue",
            "ß"=>"ss",
			"é"=>"e",
            "aa"=>"a",
			"ae"=>"e",
			"aj"=>"ei",			
            "ah"=>"a",
            "ai"=>"ei",
            "ar"=>"a",
            "ay"=>"ei",		
            "ck"=>"k",
            "ch"=>"g",			
            "cs"=>"tsch",
            "cx"=>"x",
            "cz"=>"tsch",
            "c"=>"k",			
            "dt"=>"t",
            "dz"=>"z",
            "ee"=>"e",
            "eh"=>"e",
            "el"=>"l",
            "en"=>"n",			
            "er"=>"a",
            "ey"=>"ei",
            "gk"=>"k",
            "ie"=>"i",
            "ih"=>"i",
            "ij"=>"i",			
            "kk"=>"k",
            "ks"=>"x",
            "kx"=>"x",
            "mm"=>"m",
            "nk"=>"ng",
            "nn"=>"n",
            "nt"=>"nd",
            "oh"=>"o",
            "oi"=>"eu",
            "oo"=>"o",
            "or"=>"a",
            "ou"=>"u",
            "pf"=>"f",
            "ph"=>"f",
            "qx"=>"x",
            "rt"=>"rd",
            "sz"=>"sch",
            "th"=>"t",
            "ti"=>"zi",
            "ts"=>"z",
            "tt"=>"t",
            "tz"=>"z",
            "uh"=>"u",
            "ur"=>"a",
            "uu"=>"u",
            "v"=>"f",
            "y"=>"i",
            "yi"=>"i",			
            "yr"=>"a",			
            "zz"=>"z",
            " "=>"_",
            "-"=>"_",
            "'"=>"_",
            "/"=>"_",
            "&"=>"_"
            );

    for($i=0;$i<3;$i++) {			
	foreach ($substitution as $letter=>$substitute) {
	    $word=str_replace($letter,$substitute,$word);
	    }
	}
	return $word;

	}

      /*----------------------------------------------------------------------------------
	Regional functions
	Functions to retrieve regional information
	----------------------------------------------------------------------------------*/

	function getCountries()
	{
		$db	=  JFactory::getDBO();
		$query = "SELECT sid, ressource FROM ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_selectors WHERE name='country' AND selectable='1' AND published = '1' ORDER by ressource";				
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$arr[0] = array('value' => '0', 'text' => JText::_('KISS_GENERAL_NONE')); 
		foreach ($rows as $row) {
			 $arr[$row->sid] = array('value' => $row->sid, 'text' => JText::_($row->ressource)); 
			}
		return $arr;
	}

	function getFedstates($country = 0)
	{
		$countryselect = ($country > 0) ? "AND f.cnt_id = '$country'": "";			
		$db	=  JFactory::getDBO();
		$query = "SELECT f.id as fed_id, f.cnt_id AS ctr, f.ressource AS statename FROM ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_reg_fedstate as f 
					INNER JOIN ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_selectors AS c ON (f.cnt_id = c.sid) 
					WHERE f.published = '1' AND c.name='country' AND c.published = '1' 
					$countryselect ORDER by f.ressource";
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$arr[0] = array('value' => '0', 'text' => JText::_('KISS_GENERAL_NONE')); 
		$arr[0]['country'] = '0';		
		foreach ($rows as $row) {
			 $arr[$row->fed_id] = array('value' => $row->fed_id, 'text' => JText::_($row->statename)); 
			 $arr[$row->fed_id]['country'] = $row->ctr; 			 
			}
		return $arr;
	}

	function getDistricts($country=0, $fedstate=0, $stateinfo=0)
	{
		$db	=  JFactory::getDBO();
		$countryselect = ($country > 0) ? "AND f.cnt_id = '$country'": "";		
		$stateselect = ($fedstate > 0) ? "AND fed_id = '$fedstate'": "";
		$state = ($stateinfo > 0) ? ", fed_id" : ""; 
		$query = "SELECT f.id as dis_id, f.cnt_id AS ctr $state, f.ressource AS countyname FROM ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_reg_district as f 
					INNER JOIN ".KISS_COMPONENT_JPFX.KISS_COMPONENT_TPFX."_selectors AS c ON (f.cnt_id = c.sid) 
					WHERE f.published = '1' AND c.name='country' AND c.published = '1' 
					$countryselect $stateselect ORDER by f.ressource";	
		$db->setQuery($query);
		$rows = $db->loadObjectList();
		$arr[0] = array('value' => '0', 'text' => JText::_('KISS_GENERAL_NONE'), 'country' => '0');
		if ($stateinfo) {
		    $arr[0]['state'] = '0';
		    }
		foreach ($rows as $row) {
			 $arr[$row->dis_id] = array('value' => $row->dis_id, 'text' => JText::_($row->countyname), 'country' => $row->ctr); 
			 if ($stateinfo) {
			      $arr[$row->dis_id]['state'] = $row->fed_id;
			      }
			}
		return $arr;
	}
	
    /*----------------------------------------------------------------------------------
	Database functions
	Functions to retrieve table and field information
	----------------------------------------------------------------------------------*/

	/**
	 * Retrieves information whether a table exists in the database
	 *
	 * @access	public
	 * @param 	string 		The table name
	 * @return	boolean		true if table exists, otherwise false
	 */

	function table_exists( $table_name )
	{
		$db				= JFactory::getDBO();
		$tables			=  $db->getTableList();
		$prefix 		=  $db->getPrefix();
		if (stripos($table_name, $prefix) < 1) {
		    $table_name = $prefix.$table_name;
		    }
		$tables=$db->getTableList();
		foreach ($tables as $tblval) {
		
		if ($tblval == $table_name) {
		   return true;
		   }
		}
		return false;
	}

	/**
	 * Retrieves information whether a field exists in a defined database table
	 *
	 * @access	public
	 * @param 	string 		The table name
	 * @param 	string 		The field name
	 * @return	boolean		true if field exists, otherwise false
	 */
	function field_exists( $table_name, $field_name )
	{
		$db				= JFactory::getDBO();
		$tables			=  $db->getTableList();
		$prefix 		=  $db->getPrefix();

		if (stripos($table_name, $prefix) < 1) {
		    $table_name = $prefix.$table_name;
		    }

		$tables=$db->getTableList();
		foreach ($tables as $tblval) {
		if ($tblval = $table_name) {
		   $fields = $db->getTableColumns( $tblval, false);		  
			if (array_key_exists($field_name, $fields)) {
			    return true;
			}
		   }
		}
		return false;
	}
	
	/**
	 * Method to check if the user have access to category
	 * Display or hide the not accessible categories - subcat folder will be not displayed
	 * Check whether category access level allows access
	 *
	 * E.g.: Should the link to Subcategory or to Parentcategory be displayed
	 * E.g.: Should the delete button displayed, should be the upload button displayed
	 *
	 * @param string $params rightType: accessuserid, uploaduserid, deleteuserid - access, upload, delete right
	 * @param int $params rightUsers - All selected users which should have the "rightType" right
	 * @param int $params rightGroup - All selected Groups of users(public, registered or special ) which should have the "rT" right
	 * @param int $params userAID - Specific group of user who display the category in front (public, special, registerd)
	 * @param int $params userId - Specific id of user who display the category in front (1,2,3,...)
	 * @param int $params Additional param - e.g. $display_access_category (Should be unaccessed category displayed)
	 * @return boolean 1 or 0
	 */
	 
	 function getUserRight($rightType = 'accessuserid', $rightUsers, $rightGroup = 0, $userAID = array(), $userId = 0 , $additionalParam = 0 ) {	
		
		// User ACL
		$rightGroupAccess = 0;
		// User can be assigned to different groups
		foreach ($userAID as $keyUserAID => $valueUserAID) {
			if ((int)$rightGroup == (int)$valueUserAID) {
				$rightGroupAccess = 1;
				break;
			}
		}
		
		
		$rightUsersIdArray = array();
		if (!empty($rightUsers)) {
			$rightUsersIdArray = explode( ',', trim( $rightUsers ) );
		} else {
			$rightUsersIdArray = array();
		}

		$rightDisplay = 1;
		if ($additionalParam == 0) { // We do not want to display unaccessable categories ($display_access_category)
			if ($rightGroup != 0) {
			
				if ($rightGroupAccess == 0) {
					$rightDisplay  = 0;
				} else { // Access level only for one registered user
					if (!empty($rightUsersIdArray)) {
						// Check if the user is contained in selected array
						$userIsContained = 0;
						foreach ($rightUsersIdArray as $key => $value) {
							if ($userId == $value) {
								$userIsContained = 1;// check if the user id is selected in multiple box
								break;// don't search again
							}
							// for access (-1 not selected - all registered, 0 all users)
							if ($value == -1) {
								$userIsContained = 1;// in multiple select box is selected - All registered users
								break;// don't search again
							}
						}

						if ($userIsContained == 0) {
							$rightDisplay = 0;
						}
					} else {
						
						// Access rights (Default open for all)
						// Upload and Delete rights (Default closed for all)
						switch ($rightType) {
							case 'accessuserid':
								$rightDisplay = 1;
							break;
							
							Default:
								$rightDisplay = 0;
							break;
						}
					}
				}	
			}
		}
		return $rightDisplay;
	}

	
	/*
	 *
	 */
	public function getNeededAccessLevels() {
	
		$paramsC 				= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$registeredAccessLevel 	= $paramsC->get( 'registered_access_level', array(2,3,4) );
		return $registeredAccessLevel;
	}
	
	/*
	 * Check if user's groups access rights (e.g. user is public, registered, special) can meet needed Levels
	 */
	
	public function isAccess($userLevels, $neededLevels) {
		
		$rightGroupAccess = 0;
		
		// User can be assigned to different groups
		foreach($userLevels as $keyuserLevels => $valueuserLevels) {
			foreach($neededLevels as $keyneededLevels => $valueneededLevels) {
			
				if ((int)$valueneededLevels == (int)$valueuserLevels) {
					$rightGroupAccess = 1;
					break;
				}
			}
			if ($rightGroupAccess == 1) {
				break;
			}
		}
		return (boolean)$rightGroupAccess;
	}
	
	
    /*----------------------------------------------------------------------------------
	getUserLanguage
	retreives given user's admin language 
	if no user number is given, the site's language is returned
	------------------------------------------------------------------------------------*/
	function getUserLanguage($usr = 0) {		
		$paramsC 		= JComponentHelper::getParams(KISS_COMPONENT_JDIR);		
		$db 			= JFactory::getDBO();
		$deflang		= $paramsC->get('notification_language', 'de-DE');
		if ($usr) {
			$query = 'SELECT * FROM '.$db->quoteName(KISS_COMPONENT_JPFX.'users') . ' WHERE id = ' . $db->quote($usr);
			$db->setQuery( $query );
			if ($row = $db->loadObject()) {
				$registry = new JRegistry($row->params);
				$lng = $registry->get('language', JFactory::getLanguage()->getDefault());
				return ($lng == '') ? $deflang : $lng;
				} else {
				return $deflang;						
				}
		} else {
			return $deflang;		
		}
	}

    /*----------------------------------------------------------------------------------
	KISS Admin Mail Notification Functions
	Functions to send messages by email
	------------------------------------------------------------------------------------
	param id			ID Number 
	param filename	Name of the file which was down- or uploaded
	param method	 1 = file download, message to admin
					 2 = file upload, message to admin
					 3 = entry deleted during pruning, message to admin
					 4 = entry prolongation during pruning, message to admin
					 5 = new entry submitted via frontend					
					 6 = customer account created via frontend
					 7 = comment submitted via frontend
					 8 = user message submitted via frontend
					 9 = user entry updated via frontend
					10 = user entry deleted via frontend					
	param data		Database record object
	param info		Array with additional informations					
	 */
	function sendKissAdminMail ( $id = 0, $fileName, $method = 1, $data, $info = array()) {

		// Load the parameters.
		$app 		= JFactory::getApplication();
		$paramsC 	= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$db 		= JFactory::getDBO();
		$sitename 	= $app->getCfg( 'sitename' );
		$fromname	= $sitename;
		$date		= JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' ));
		$user 		= JFactory::getUser();
		$link0 		= JURI::root()."index.php?option=".KISS_COMPONENT_JDIR;
		$link1 		= JURI::root()."administrator".DS."index.php?option=".KISS_COMPONENT_JDIR;		
		$lang		= JFactory::getLanguage();
	    $today 		= date("Y-m-d H:i:s");
		$timestamp 	= time();
		$fields		= self::getFormFields();
		$mailer		= new JMail();
		
		// Load the notification language for admins
		$lang->load(self::getUserLanguage());
		
		if ($paramsC->get('notify_email_from')) {
			$email = $paramsC->get('notify_email_from');
			} else {
			$mailfrom 	= $app->getCfg( 'mailfrom' );
			}
			
		// Retrieve some user data
		$uprofile = self::getUserProfile($data->uid);
		$name = (isset($uprofile['firstname'])) ? $uprofile['firstname']." ".$uprofile['name'] :$uprofile['name'];
		$userName = (isset($uprofile['username'])) ? $uprofile['username'] : JText::_('KISS_GENERAL_ANONYMOUS');
		$email = (isset($uprofile['email'])) ? $uprofile['email'] : '';
		if (strlen($email) < 1 && strlen($data->it_contact_email) > 0) {
			$email = ($field['it_contact_email']->encrypted) ? self::decrypted($data->it_contact_email) : $data->it_contact_email;
			}
		$uname = $name;
		if (isset($uprofile['address'])) {
			$uname .= ", ".$uprofile['postcode']." ".$uprofile['city'];
			}
		
		if (isset($data)){
			$adid		= $data->id;
			$adident	= $data->it_ident;		
			$dtitle		= $data->title;
			$intro		= $data->it_intro;
			$createdate	= date($paramsC->get('dateformat', 'd.m.Y'), strtotime($data->date_created));
			$usernumber	= $data->uid;	
			$plan		= self::getUserPlan($usernumber);
			$prune		= ($plan->expiry > 0) ? $plan->expiry : 730;
			$expirydate	= date($paramsC->get('dateformat', 'd.m.Y'), strtotime($data->date_created." + ".$prune." days"));	
			}

		switch ($method) {
		case 1: // File download
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_FILE_DOWNLOADED' );
			$title 			= JText::_( 'KISS_MAIL_FILE_DOWNLOADED' );
			$messageText 	= JText::_( 'KISS_GENERAL_FILE') . ' "' .$fileName . '" '.JText::_('KISS_MAIL_DOWNLOADED_BY'). ' '.$name . $userName.'.';
			break;
		case 2: // File upload
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_FILE_UPLOADED' );
			$title 			= JText::_( 'KISS_MAIL_FILE_UPLOADED' );
			$messageText 	= JText::_( 'KISS_GENERAL_FILE') . ' "' .$fileName . '" '.JText::_('KISS_MAIL_UPLOADED_BY'). ' '.$name . $userName.'.';
			break;
		case 3: // Entry deleted
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$title 			= JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_ENTRY_DELETED_TEXT_ADMIN'), $name, $sitename, $dtitle, $intro, $uname, $createdate);
			break;
		case 4: // Entry prolongation
			$link 			= $link0 . "&task=prolongation&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_ENTRY_PROLONGATION' );
			$title 			= JText::_( 'KISS_MAIL_ENTRY_PROLONGATION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_ENTRY_PROLONGATION_TEXT_ADMIN'), $name, $sitename, $plan->prolong, $adident, $dtitle, $intro, $link);
			break;
		case 5: // New entry submitted
			$link 			= $link0 . "&view=item&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_NEWENTRY_ADMIN_SUBJECT' );
			$title 			= JText::_( 'KISS_MAIL_NEWENTRY_ADMIN_SUBJECT' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_NEWENTRY_ADMIN_BODY'), '_NAMEPLACEHOLDER_', $sitename, $adident, $dtitle, $intro, $uname, $expirydate, $link);
			break;
		case 7: // Comment submitted
			$link 			= $link0 . "&view=item&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_COMMENT_SUBMISSION' );
			$title 			= JText::_( 'KISS_MAIL_COMMENT_SUBMISSION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_COMMENT_SUBMISSION_TEXT_ADMIN'), '_NAMEPLACEHOLDER_', $sitename, $info['commenttitle'], $info['commenttext'], $adident, $dtitle, $intro, $link);
			break;
		case 8: // Message submitted
			$link 			= $link0 . "&view=item&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_MESSAGE_SUBMISSION' );
			$title 			= JText::_( 'KISS_MAIL_MESSAGE_SUBMISSION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_MESSAGE_SUBMISSION_TEXT_ADMIN'), '_NAMEPLACEHOLDER_', $sitename, $info['from'], $info['subject'], $info['message'], $adident, $dtitle, $intro, $link);
			break;
		case 9: // User entry updated
			$link 			= $link0 . "&view=item&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_UPDENTRY_ADMIN_SUBJECT' );
			$title 			= JText::_( 'KISS_MAIL_UPDENTRY_ADMIN_SUBJECT' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_UPDENTRY_ADMIN_BODY'), '_NAMEPLACEHOLDER_', $sitename, $adident, $dtitle, $intro, $uname, $expirydate, $link);
			break;
		case 10: // User entry deleted
			$link 			= $link1 . "&task=".KISS_COMPONENT_FILE."item.edit&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$title 			= JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_DELENTRY_ADMIN_BODY'), '_NAMEPLACEHOLDER_', $sitename, $adident, $dtitle, $intro, $uname, $expirydate, $link);
			break;
		}
		$message = $title . "\n\n"
		. JText::_( 'KISS_GENERAL_FIELD_WEBSITE_LABEL' ) . ': '. $sitename . "\n"
		. JText::_( 'KISS_GENERAL_DATE' ) . ': '. $date . "\n"
		. 'IP: ' . $_SERVER["REMOTE_ADDR"]. "\n\n"
		. JText::_( 'KISS_MAIL_MESSAGE' ) . ': '."\n"
		. "\n\n"
		. $messageText
		. "\n\n"
		. JText::_( 'KISS_MAIL_REGARDS' ) .", \n"
		. $sitename ."\n";
							
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($messageText, ENT_QUOTES);
				
		//get all super administrators
		if (self::field_exists('users', 'firstname')) {
			$nametofetch = ($paramsC->get('show_details_username')) ? 'username as aname': "CONCAT_WS(' ', firstname, name) as aname";
			} else {
			$nametofetch = ($paramsC->get('show_details_username')) ? 'username as aname': "name as aname";			
			}
		$query = 'SELECT id, '.$nametofetch.', email, sendEmail' .
		' FROM ' . $db->quoteName(KISS_COMPONENT_JPFX.'users').' AS u' .
		' LEFT JOIN '.$db->quoteName(KISS_COMPONENT_JPFX.'user_usergroup_map').' AS ug ON u.id = ug.user_id' .	
		' WHERE ug.group_id = 7 OR ug.group_id = 8 AND u.sendEmail = 1';
		$db->setQuery( $query );
		$rows = $db->loadObjectList();

		foreach ($rows as $row) {
			if (JMailHelper::isEmailAddress($paramsC->get('notify_email'))) {
				$email = $paramsC->get('notify_email');
				} else {
				if (JMailHelper::isEmailAddress($row->email)) {
					// Notify via email				
					$email 	= $row->email;
					if ($paramsC->get('notify_direct_admin', 1) == 1) {
						$mailer->sendMail($mailfrom, $fromname, $email, $subject, str_replace('_NAMEPLACEHOLDER_',$row->aname,$message));
						} else {
						// Notify via messaging system
						$messagingText	= str_replace('_NAMEPLACEHOLDER_',$row->aname,nl2br($message));
						$messageText 	= sprintf(JText::_('KISS_MAIL_MESSAGING_NOTIFICATION'), $row->aname, $sitename);
						$mailer->sendMail($mailfrom, $fromname, $email, $subject, $messageText);
						// Is uddeim installed ?
						if (self::table_exists('uddeim') && self::field_exists('uddeim', 'message') && $this->pre->l4) {						
						$query = "INSERT INTO ".KISS_COMPONENT_JPFX."uddeim (`disablereply`, `fromid`, `toid`, `datum`, `totrash`, `message`)
									VALUES ( 1, ".$user->get('id').", $row->id, '$timestamp', 0, '$messagingText')
									";
						} else {
						$query = "INSERT INTO ".KISS_COMPONENT_JPFX."messages (`state`, `user_id_from`, `user_id_to`, `date_time`, `subject`, `message`)
									VALUES ( 0, ".$user->get('id').", $row->id, '$today', '$subject', '$messagingText')
									";
						}
					
						$db->setQuery($query);
						$db->query();						
					}
				}
			}
		}
		return true;
	}

	/*------------------------------------------------------------------
	getUserProfile:
	Returns a user profile array from a given user
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/
	function getUserProfile ($usr = 0) {
		$db				= JFactory::getDBO();
		$juser			= JFactory::getUser($usr);
		$user			= array();
		$lang 			= JFactory::getLanguage();
		$language 		= $lang->getTag();

		// Set default values
		$user['name'] = ''; $user['firstname'] = ''; $user['avatar'] = ''; $user['address'] = ''; $user['city'] = ''; $user['phone'] = ''; $city = '';
		$user['email'] = ''; $user['website'] = ''; $user['postcode'] = ''; $user['address_1'] = ''; $user['address_2'] = ''; $postcode = '';
		// Get the user profile plugin from Joomla
		if (JPluginHelper::getPlugin('user', 'profilekiss')) {
			$uplugin 	= JPluginHelper::getPlugin('user', 'profilekiss');	
			$plugname	= 'profilekiss';
			} else {
			$uplugin 	= JPluginHelper::getPlugin('user', 'profile');
			$plugname	= 'profile';
			}
		$query = 'SELECT * FROM '.$db->quoteName('#__user_profiles') . ' WHERE user_id = ' . $db->quote($juser->id) . ' AND profile_key LIKE '.$db->quote('%'.$plugname.'%');			
		$db->setQuery($query);
		$uplugin_params = $db->loadObjectList();


		// Anonymous user, we are done
		if ($usr == 0) {
			$user['name'] = JText::_('KISS_GENERAL_UNKNOWN');
			$user['firstname'] = '';
			$user['firstname'] = '';			
			$user['avatar'] = JURI::root().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-48-anonymous.png';
			$user['language'] = $language;			
			} else {
			// Get user's base data
			$user['name'] = $juser->name;
			$user['email'] = $juser->email;
			$user['username'] = $juser->username;
			$user['language'] = self::getUserLanguage($usr);
			$user['avatar'] = JURI::root().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-48-anonymous.png';							
			if (self::field_exists('users', 'firstname')) {
				$user['firstname'] = $juser->firstname;
				if (file_exists(JPATH_ROOT.DS.'media'.DS.'users'.DS.'images'.DS.$juser->id.DS.$juser->img_url)) {
					$user['avatar'] = JURI::root().'media'.DS.'users'.DS.'images'.DS.$juser->id.DS.$juser->img_url;			
					}
				}

			// Get user profile from Joomla			
			if (JPluginHelper::isEnabled('user', $plugname)) {
				foreach ($uplugin_params as $parm) {
					if ($parm->profile_key == $plugname.'.address1') { $user['address'] = str_replace('"', '', $parm->profile_value); $user['address1'] = str_replace('"', '', $parm->profile_value);} 
					if ($parm->profile_key == $plugname.'.address2') { $user['address'] .= " ".str_replace('"', '', $parm->profile_value); $user['address2'] = str_replace('"', '', $parm->profile_value);}				
					if ($parm->profile_key == $plugname.'.city') { $city = " ".str_replace('"', '', $parm->profile_value); }								
					if ($parm->profile_key == $plugname.'.postal_code') { $postcode = " ".str_replace('"', '', $parm->profile_value); }												
					if ($parm->profile_key == $plugname.'.website') { $user['website'] = " ".str_replace('"', '', $parm->profile_value); }																
					if ($parm->profile_key == $plugname.'.phone') { $user['telephone'] = " ".str_replace('"', '', $parm->profile_value); }																
					if ($parm->profile_key == $plugname.'.gender') { $user['gender'] = " ".str_replace('"', '', $parm->profile_value); }																				
					}
				$user['city'] = trim($city);					
				$user['postcode'] = trim($postcode);
				$user['location'] = $postcode." ".$city;									
				$user['language'] = self::getUserLanguage($usr) ;					
				}

			// ----------------TODO: Jomsocial integration---------------------------------------
			
			// ----------------TODO: CB integration----------------------------------------------			

			// ----------------TODO: CBE integration---------------------------------------------			
			}
		return $user;
	}
	
    /*----------------------------------------------------------------------------------
	KISS User Mail Notification Functions
	Functions to send messages by email
	------------------------------------------------------------------------------------
	param id		User ID Number (the email's addressee)
	param filename	Name of the file which was down- or uploaded
	param method		1 = message was submitted
					2 = comment was submitted
					3 = entry deleted during pruning, message to user
					4 = entry prolongation during pruning, message to user
					5 = new entry by user via frontend
					6 = entry expiration message
					7 = rss message about new entries in category
	param data		Database record object
	param info		Array with additional informations
					
	 */
	function sendKissUserMail ( $uid = 0, $fileName, $method = 1, $data, $info = array()) {

		// Load the parameters.
		$app 		= JFactory::getApplication();
		$paramsC 	= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		$db 		= JFactory::getDBO();
		$sitename 	= $app->getCfg( 'sitename' );
		$fromname	= $sitename;
		$date		= JHTML::_('date',  gmdate('Y-m-d H:i:s'), JText::_( 'DATE_FORMAT_LC2' ));
		$user 		= JFactory::getUser();
		$link0 		= JURI::root()."index.php?option=".KISS_COMPONENT_JDIR;
	    $today 		= date("Y-m-d H:i:s");
		$timestamp 	= time();
		$mailer		= new JMail();
		
		if ($paramsC->notify_email_from) {
			$email = $paramsC->notify_email_from;
			} else {
			$mailfrom 	= $app->getCfg( 'mailfrom' );
			}
		
		if (isset($user->username) && $user->username != '') {
			$senderName = ' ('.self::getUserName($user->get('id')).')';
		} else {
			$senderName = '';
		}
		// Retrieve some user data
		$uprofile = self::getUserProfile($data->uid);
		$name = (isset($uprofile['firstname'])) ? $uprofile['firstname']." ".$uprofile['name'] :$uprofile['name'];
		$userName = (isset($uprofile['username'])) ? $uprofile['username'] : JText::_('KISS_GENERAL_ANONYMOUS');
		$email = (isset($uprofile['email'])) ? $uprofile['email'] : '';
		if (strlen($email) < 1 && strlen($data->it_contact_email) > 0) {
			$email = ($field['it_contact_email']->encrypted) ? self::decrypted($data->it_contact_email) : $data->it_contact_email;
			}
		$uname = $name;
		if (isset($uprofile['address'])) {
			$uname .= ", ".$uprofile['postcode']." ".$uprofile['city'];
			}

		if (isset($data)){
			$adid		= $data->id;
			$adident	= $data->it_ident;		
			$dtitle		= $data->title;
			$intro		= $data->it_intro;
			$createdate	= date($paramsC->get('dateformat'), $data->date_created);
			$usernumber	= $data->uid;	
			$plan		= self::getUserPlan($usernumber);
			$prune		= ($plan->expiry > 0) ? $plan->expiry : $plan->duration;
			$expirydate	= date($paramsC->get('dateformat'), strtotime($data->date_created." + ".$prune." days"));	
			}
		switch ($method) {
		case 1: // Message submitted
			$link 			= $link0 . "&view=item&id=".$adid;;		
			$subject 		= $info['subject'];
			$mailfrom		= $info['from'];
			$title 			= JText::_( 'KISS_MAIL_MESSAGE_SUBMISSION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_MESSAGE_SUBMISSION_TEXT_USER'), $name, $sitename, $adident, $dtitle, $intro, $info['message'], $link);		
			break;
		case 2: // Comment submitted
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_COMMENT_SUBMISSION' );
			$title 			= JText::_( 'KISS_MAIL_COMMENT_SUBMISSION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_COMMENT_SUBMITTED_TEXT_USER'), $name, $sitename, $adident, $dtitle, $intro, $info['commenttitle'], $info['commenttext']);
			break;
		case 3: // Entry deleted during pruning
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$title 			= JText::_( 'KISS_MAIL_ENTRY_DELETED' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_ENTRY_DELETED_TEXT_USER'), $name, $sitename, $dtitle, $intro, $uname, $createdate);
			break;
		case 4: // Entry prolongation during pruning
			$link 			= $link0 . "&task=prolongation&id=".$adid;;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_ENTRY_PROLONGATION' );
			$title 			= JText::_( 'KISS_MAIL_ENTRY_PROLONGATION' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_ENTRY_PROLONGATION_TEXT_USER'), $name, $sitename, $plan->prolong, $adident, $dtitle, $intro, $link);
			break;
		case 5: // New entry submitted
			$link 			= $link0 . "&view=item&id=".$adid;
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_NEWENTRY_USER_SUBJECT' );
			$title 			= JText::_( 'KISS_MAIL_NEWENTRY_USER_SUBJECT' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_NEWENTRY_USER_BODY'), $name, $sitename, $adident, $dtitle, $intro, $uname, $expirydate, $link);
			break;
		case 6: // Entry expiration message
			$link 			= $link0 . "&view=account";
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_EXPENTRY_USER_SUBJECT' );
			$title 			= JText::_( 'KISS_MAIL_EXPENTRY_USER_SUBJECT' );
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_EXPENTRY_USER_BODY'), $name, $sitename, $adident, $dtitle, $intro, $uname, $expirydate, $link);
			break;
		case 7: // New entry in category (rss notification)
			$link 			= $link0 . "&view=account";
			$subject 		= $sitename. ' - ' . JText::_( 'KISS_MAIL_EXPENTRY_USER_SUBJECT' );
			$title 			= sprintf(JText::_( 'KISS_MAIL_NEWENTRY_CATEGORY_SUBJECT' ), $sitename);
			$messageText 	= sprintf(JText::_( 'KISS_MAIL_NEWENTRY_CATEGORY_BODY'), $name, $sitename, $adident, $dtitle, $intro, $createdate, $expirydate, $link);
		}
		
		$message = $title . "\n\n"
		. JText::_( 'KISS_GENERAL_FIELD_WEBSITE_LABEL' ) . ': '. $sitename . "\n"
		. JText::_( 'KISS_GENERAL_DATE' ) . ': '. $date . "\n"
		. JText::_( 'KISS_MAIL_MESSAGE' ) . ': '."\n"
		. "\n\n"
		. $messageText
		. "\n\n"
		. JText::_( 'KISS_MAIL_REGARDS' ) .", \n"
		. $senderName ."\n"
		. $sitename ."\n";
							
		$subject = html_entity_decode($subject, ENT_QUOTES);
		$message = html_entity_decode($messageText, ENT_QUOTES);
				
		//get all registered users
		$query = 'SELECT id, name, email, sendEmail' .
		' FROM '.KISS_COMPONENT_JPFX.'users WHERE id = ' . $uid;
		$db->setQuery( $query );
		$row = $db->loadObject();
		$tomail = (isset($email) && strlen($email)) ? $email : $row->email;
		if (JMailHelper::isEmailAddress($tomail)) {
			// Notify via email				
			if ($paramsC->get('notify_direct_admin', 0) == 0) {
				$mailer->sendMail($mailfrom, $fromname, $tomail, $subject, $message);
				} else {
					// Notify via messaging system
					$messagingText	= $message;
					$messageText 	= sprintf(JText::_('KISS_MAIL_MESSAGING_NOTIFICATION'), $name, $sitename);
					$mailer->sendMail($mailfrom, $fromname, $tomail, $subject, $messageText);
					// Is uddeim installed ?
					if (self::table_exists('uddeim') && self::field_exists('uddeim', 'message') && $this->pre->l4) {						
					$query = "INSERT INTO ".KISS_COMPONENT_JPFX."uddeim (`disablereply`, `fromid`, `toid`, `datum`, `totrash`, `message`)
								VALUES ( 1, ".$user->get('id').", ".$user->get('id').", '$timestamp', 0, '$messagingText')
								";
					} else {
					$query = "INSERT INTO ".$db->quoteName(KISS_COMPONENT_JPFX."messages") . " (`state`, `user_id_from`, `user_id_to`, `date_time`, `subject`, `message`)
								VALUES ( 0, ".$user->get('id').", ".$user->get('id').", '$today', '$subject', '$messagingText')
								";
					}
				$db->setQuery($query);
				$db->query();
				}	
			}			
		return true;
	}

	function setQuestionmarkOrAmp($url) {
		$isThereQMR = false;
		$isThereQMR = preg_match("/\?/i", $url);
		if ($isThereQMR) {
			return '&amp;';
		} else {
			return '?';
		}
	}


	/*------------------------------------------------------------------
	getUserName:
	retrieves the name of a given user
	Copyright (c) 2013 by KISS Software
	-------------------------------------------------------------------*/
	function getUserName($uid = 0) {
		$db			= JFactory::getDBO();
		$paramsC 	= JComponentHelper::getParams(KISS_COMPONENT_JDIR) ;
		
		if (self::field_exists('users', 'firstname')) {
			$nametofetch = ($paramsC->get('show_details_username')) ? 'u.username': "CONCAT_WS(' ', u.firstname, u.name)";
		    $query = "SELECT u.id AS value, $nametofetch AS text";
		    } else {
			$nametofetch = ($paramsC->get('show_details_username')) ? 'u.username': 'u.name';			
		    $query = "SELECT u.id AS value, $nametofetch AS text";
		    }
		$query .= ' FROM '.KISS_COMPONENT_JPFX.'users AS u'
		. ' WHERE u.id = '.$uid;
		$db->setQuery($query);
		if($result = $db->loadObject()) {
			$txt = $result->text;
			} else {
			$txt = JText::_('KISS_GENERAL_UNKNOWN');
			}
		return $txt;
	}
 

	/*------------------------------------------------------------------
	geocode_address:
	geocodes an address and checks it with Google Maps
	Copyright (c) 2013 by KISS Software
	-------------------------------------------------------------------*/
	function geocode_address($location)
	{
		$params		 = JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		if ($gplugin = JPluginHelper::getPlugin('system', 'plugin_googlemap3')) { 
			$gplugin_params = new JRegistry($gplugin->params);
			$gwebsite = $gplugin_params->get( 'googlewebsite', 'maps.google.com' );
			$key = trim($gplugin_params->get( 'Google_API_key', '' ));
			$iso = "UTF-8";
			$geodata = self::get_geo($location);
			$coords = $geodata['coords'];
			$longitude = $coords['lng'];
			$latitude = $coords['lat'];
			$street = $geodata['street'];
			$city = $geodata['city'];
			$zip = $geodata['zip'];
			$country = $geodata['country'];
			$country_iso = $geodata['country_iso'];			
			$regbez = $geodata['regbez'];
			$statelong = $geodata['state_long'];
			$stateshort = $geodata['state_short'];
			$suburb = ($params->get('include_address_suburbs')) ? $geodata['suburb'] : '';
			$kreis = $geodata['kreis'];
			// Repair some of Google's odd returns
			if (strlen(trim($params->get('geocoded_city_original'))) > 0 && strlen(trim($params->get('geocoded_city_replaced'))) > 0) {
			$retarr = explode(",",$params->get('geocoded_city_original'));
			$reparr = explode(",",$params->get('geocoded_city_replaced'));				
			for ($i=0, $n=count( $retarr ); $i < $n; $i++) {
					if (stripos($city, $retarr[$i]) !== false) { 
						$city = $reparr[$i];
						break;
						}		
				}
			}			
			// Adjust Address and City if function is set
			if (strlen($suburb) && strtolower($city) != strtolower($suburb)){
				$city .= "-".$suburb;
				}
			$locat = $street.",".$zip.",".$city.",".$suburb.",".$country.",".$statelong.",".$regbez.",".$longitude.",".$latitude.",".$kreis.",".$stateshort.",".$country_iso;				
			} else {
			$locat = "not,geocoded";
			}// End if ($gplugin ...)

		return $locat;
	}
	
 /*------------------------------------------------------------------
	get_geo:
	returns a geocode of a given address from Google Maps
	Copyright (c) 2010 by KISS Software
  -------------------------------------------------------------------*/
	function get_geo($address)
	{
		$coords = '';
		$getpage='';
		$replace = array("\n", "\r", "&lt;br/&gt;", "&lt;br /&gt;", "&lt;br&gt;", "<br>", "<br />", "<br/>");
		$address = str_replace($replace, '', $address);
	
		$logmessage = "Address: ".$address."<br />";
		$uri = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false";		

		if (function_exists('curl_init')) {
			$logmessage .="curl_init exists <br />";
			$ch = curl_init();
			$timeout = 5; // set to zero for no timeout
			curl_setopt ($ch, CURLOPT_URL, $uri);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$getpage = curl_exec($ch);
			curl_close($ch);
			}
			
        $resp = json_decode($getpage, true);
        if($resp['status']='OK'){
			foreach ($resp['results'][0]['address_components'] as $info) {
				if ($info['types'][0] == 'locality') {$city = $info['long_name'];}
				if ($info['types'][0] == 'sublocality') {$suburb = $info['long_name'];}				
				if ($info['types'][0] == 'country') {$country = $info['long_name']; $country_iso = $info['short_name'];}
				if ($info['types'][0] == 'route') {$streetname = $info['long_name'];}				
				if ($info['types'][0] == 'street_number') {$streetnumber = $info['long_name'];}
				if ($info['types'][0] == 'postal_code') {$plz = $info['long_name'];}
				if ($info['types'][0] == 'administrative_area_level_1') {$statelong = $info['long_name']; $stateshort = $info['short_name'];}				
				if ($info['types'][0] == 'administrative_area_level_2') {$regbez = $info['long_name'];}				
				if ($info['types'][0] == 'administrative_area_level_3') {$kreis = $info['long_name'];}								
				}
		
            $coords = $resp['results'][0]['geometry']['location'];
			$street = $streetname .' '. $streetnumber;
			$formatted = $resp['results'][0]['formatted_address'];	
			$geodata = array(
				'coords' => $coords,
				'street' => $street,
				'city' => $city,
				'zip' => $plz,
				'regbez' => $regbez,
				'kreis' => $kreis,		
				'state_long' => $statelong,
				'state_short' => $stateshort,
				'country' => $country,
				'country_iso' => $country_iso,
				'suburb' => $suburb,
				'formatted' => $formatted,
				);
			} else {
			$geodata = array();
        }
		return $geodata;
	}		
	
	/*------------------------------------------------------------------
	renderMedia:
	Renders the output of a media player for specified media
	Copyright (c) 2014 KISS Software
	-------------------------------------------------------------------*/
	function renderMedia($tagOrder) {
		$option = JRequest::getVar( 'option', '', '', 'string');
		include_once(JPATH_ROOT .DS.'components'.DS.$option.DS.'assets'.DS.'players'.DS.'sources.php');
		include_once(JPATH_ROOT .DS.'components'.DS.$option.DS.'assets'.DS.'players'.DS.'helper.php');
		$live_site 	= JURI::root();
		$user 		= JFactory::getUser();
		$user_id 	= $user->get( 'id' );
		$paramsC 	= JComponentHelper::getParams(KISS_COMPONENT_JDIR);
		
		foreach ($tagReplace as $plg_tag => $value) {
			// expression to search for
			$regex = "#{".$plg_tag."}(.*?){/".$plg_tag."}#s";
			// process tags

			if (preg_match_all($regex, $tagOrder, $matches, PREG_PATTERN_ORDER) > 0) {
				// start the replace loop
				foreach ($matches[0] as $key => $match) {
					$tagcontent 		= preg_replace("/{.+?}/", "", $match);
					$tagparams 			= explode('|',$tagcontent);
					$tagsource 			= trim(strip_tags($tagparams[0]));
					$final_vwidth 		= (@$tagparams[1]) ? $tagparams[1] : $paramsC->get('med_width');
					$final_vheight 		= (@$tagparams[2]) ? $tagparams[2] : $paramsC->get('med_height');
					$final_autoplay 	= (@$tagparams[3]) ? $tagparams[3] : $paramsC->get('med_autoplay');
					$final_transparency = (@$tagparams[4]) ? $tagparams[4] : $paramsC->get('med_transparency');
					$final_background	= (@$tagparams[5]) ? $tagparams[5] : $paramsC->get('med_background');
					$final_backgroundqt	= (@$tagparams[6]) ? $tagparams[6] : $paramsC->get('med_backgroundQT');	
					$final_controlbar	= (@$tagparams[7]) ? $tagparams[7] : $paramsC->get('med_controlbar');

					// source elements
					$findAVparams = array(
						"{SOURCE}",
						"{SOURCEID}",
						"{FOLDER}",
						"{WIDTH}",
						"{HEIGHT}",		
						"{AUTOPLAY}",
						"{TRANSPARENCY}",
						"{BACKGROUND}",
						"{BACKGROUNDQT}",
						"{CONTROLBAR}",
						"{SITEURL}",
					);
			
					// special treatment
					if($plg_tag=="yahoo"){
						$tagsourceyahoo = explode('/',$tagsource);
						$tagsource = 'id='.$tagsourceyahoo[1].'&amp;vid='.$tagsourceyahoo[0];
					}
					if($plg_tag=="youku") $tagsource = substr($tagsource,3);		
					
					// Prepare the HTML
					$output = new JObject;
					// replacement elements
					if(in_array($plg_tag, array("mp3","mp3remote","wma","wmaremote"))){
						$afolder = self::getImageDirforUser($user_id, 0)."videos";					
						$transparency = $paramsC->get('med_transparency');
						$background = $paramsC->get('med_background');
						$backgroundQT = $paramsC->get('med_backgroundQT');
						$controlBarLocation = $paramsC->get('med_controlbar');

						$replaceAVparams = array(
							$tagsource,
							substr(md5($tagsource),1,8),
							$afolder,
							$awidth,
							$aheight,
							$final_autoplay,
							$transparency,
							$background,
							$backgroundQT,
							$controlBarLocation,
							$live_site,
						);
						
						$output->playerWidth = $awidth;
						$output->playerHeight = $aheight;
						
					} else {
						$vfolder = self::getImageDirforUser($user_id, 0)."videos";
						$transparency = $paramsC->get('med_transparency');
						$background = $paramsC->get('med_background');
						$backgroundQT = $paramsC->get('med_backgroundQT');
						$controlBarLocation = $paramsC->get('med_controlbar');
					
						$replaceAVparams = array(
							$tagsource,
							substr(md5($tagsource),1,8),
							$vfolder,
							$final_vwidth,
							$final_vheight,
							$final_autoplay,
							$transparency,
							$background,
							$backgroundQT,
							$controlBarLocation,
							$live_site,
						);
						
						$output->playerWidth = $final_vwidth;
						$output->playerHeight = $final_vheight;					
					}
					
					$output->playerID = 'AVPlayerID_'.substr(md5($tagsource),1,8);
					$output->player = JFilterOutput::ampReplace(str_replace($findAVparams, $replaceAVparams, $tagReplace[$plg_tag]));
					$output->playerEmbedHTML = preg_replace("#(\r|\t|\n|)#s","",htmlentities($output->player, ENT_QUOTES));
					
					// Download button
					if(isset($downloadLink) && $downloadLink){
						if (in_array($plg_tag, array("flv","swf","wmv","mov","mp4","3gp","divx"))) {
							$output->downloadLink = $live_site.'components'.DS.$option.DS.'players'.DS.'download.php?file='.$vfolder.DS.$tagsource; //.'.'.$plg_tag;
						} elseif(in_array($plg_tag, array("mp3","wma"))) {
							$output->downloadLink = $live_site.'components'.DS.$option.DS.'players'.DS.'download.php?file='.$afolder.DS.$tagsource; //.'.'.$plg_tag;
						} else {
							$output->downloadLink = '';
						}
					} else {
						$output->downloadLink = '';
					}
					
					// Lightbox popup
					if(isset($lightboxLink) && $lightboxLink && !in_array($plg_tag, array("mp3","mp3remote","wma","wmaremote"))) { // video formats only
						$output->lightboxLink = '#'.$output->playerID;
					} else {
						$output->lightboxLink = '';
					}
					
					// Embed form
					if(isset ($embedForm ) && $embedForm && !in_array($plg_tag, array("wmv","wmvremote","wma","wmaremote"))){ // no Windows Media formats
						$output->embedLink = 'embed_'.$output->playerID;
					}	else {
						$output->embedLink = '';
					}

					// Fetch the template
					ob_start();
					if (!isset($plg_name)) {
						$plg_name = null;
					    }
					$getTemplatePath = AllVideosHelper::getTemplatePath($plg_name,'default.php');
					$getTemplatePath = $getTemplatePath->file;
					include($getTemplatePath);
					$getTemplate = "<!-- Video Player Start -->".ob_get_contents()."<!-- Video Player End -->";
					ob_end_clean();

					// Do the replace
					$retval = preg_replace("#{".$plg_tag."}".preg_quote($tagcontent)."{/".$plg_tag."}#s", $getTemplate , $tagOrder);
					return $retval;

				} // end foreach
	
			} // end if
		
		} // END ALLVIDEOS LOOP	
	}
	
	public function renderImageList($params, $item, $stit='', $ftit='', $directory='com_users', $filename='users', $section='categories', $fieldname='image', $watermark=0, $imagelink='', $clicklink='') {
		$o = '';
		if (is_array($item)) {
			$imgobject = $item[$fieldname];
		} else {
			$imgobject = $item->$fieldname;
		}

		if ($imgobject) {
			if ($imagelink) {
				$imagefile = $imagelink;
				$imagedisp = $imagelink;
				$exists = KissGeneralHelper::url_exists($imagefile);
				} else {
				$img_base = basename($imgobject);
				$imagefile = JPATH_ROOT.DS."media".DS.$filename.DS."images".DS.$section.DS.$imgobject;
				$imagedisp = JRoute::_(str_replace(JPATH_ROOT.DS, JURI::ROOT(), $imagefile));
				$imagelink = JRoute::_(str_replace(JPATH_ROOT.DS, JURI::ROOT(), $imagefile));
				$exists = file_exists($imagefile);
				}

			if ($exists) {
			    $img_base = basename($item->$fieldname);			
				if ($watermark) {
					if(self::getWatermarkImage($item, $directory, $filename)) {
						$path_parts = pathinfo($item->$fieldname);
						$file_name = $path_parts['filename'];
						$file_ext = $path_parts['extension'];	
						$img_base = 'thumbnails'.DS.'stamp_'.$file_name.'.'.$file_ext;
						}
					}	
				$width = $params->get('catlist_img_preview_size', 80);
				$imagedata = getimagesize($imagefile);
				$width1 = ($imagedata[0]+20 < $params->get('img_maxwidth',600)) ? $imagedata[0]+20 : $params->get('img_maxwidth',600);
				$height1 = ($imagedata[1]+20 < $params->get('img_maxheight',400)) ? $imagedata[1]+20 : $params->get('img_maxheight',400);
				$lbsize = ($width1 < 300) ? '-small' : '-large';
			   // Show image or edit ad upon image click
				switch ($params->get('behavior_img_click',1)) {
					case 0:
					$o .= "<img name=\"compimg\" class=\"compimg\" width=\"$width\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
					break;
					case 1:
					default:
					if (version_compare(JVERSION, '3.0', 'ge')) {
						if (!$params->get('save_delay', 0)) {
							$simg = "<div><img src=\'$imagelink\' width=\'$width1\' /></div><div>$ftit</div>";
							$o .= '<a href=\'#\' data-toggle=\'modal\' data-target=\'#lightbox'.$lbsize.'\' onclick="javascript:document.getElementById(\'lightboxbody'.$lbsize.'\').innerHTML=\''.$simg.'\';document.getElementById(\'modalTitle'.$lbsize.'\').innerHTML=\''.$stit.'\';" >';
							$o .= "<img name=\"compimg\" title=\"".JText::_( 'KISS_CONFIG_SHOWORIGINAL_LABEL' )."\" width=\"$width\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
							} else {
							$o .= "<a class=\"modal\" href='$imagelink' rel=\"{handler: 'iframe', size: {x: $width1, y: $height1}}\">";
							$o .= "<img name=\"compimg\" title=\"".JText::_( 'KISS_CONFIG_SHOWORIGINAL_LABEL' )."\" class=\"compimg\" width=\"$width\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
							}						
					    } else if (version_compare(JVERSION, '2.5', 'ge')) {
					    $o .= "<a class=\"modal\" href='$imagelink' rel=\"{handler: 'iframe', size: {x: $width1, y: $height1}}\">";
					    $o .= "<img name=\"compimg\" title=\"".JText::_( 'KISS_CONFIG_SHOWORIGINAL_LABEL' )."\" class=\"compimg\" width=\"$width\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
					    }
					$o .= "</a>";
					break;
					case 2:
					$o .= "<a href='$clicklink'>";
					$o .= "<img name=\"compimg\" title=\"".JText::_( 'KISS_CONFIG_SHOWDETAILS_LABEL' )."\" class=\"compimg\" width=\"$width\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
					$o .= "</a>";
					break;
					}	   
					} else {
					$img_base = 'no_image.jpg';
					$imagename = JPATH_ROOT.DS."media".DS.KISS_COMPONENT_FILE.DS."images".DS.$img_base;
					$imagedisp = JRoute::_(str_replace(JPATH_ROOT.DS, JURI::ROOT(), $imagename));						
					if (file_exists($imagename)) {
						$o .= "<img name=\"compimg\" class=\"compimg\" width=\"$width\" title=\"".JText::_('KISS_GENERAL_NO_IMAGE_AVAILABLE')."\" src=\"$imagedisp\" border=\"0\" valign=\"top\"/>";
						}
					}
				}
		return $o;
		}

	/*------------------------------------------------------------------
	getWatermarkImage:
	Creates an image with watermark.
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/

function getWatermarkImage($row, $component=KISS_COMPONENT_JDIR, $section=KISS_COMPONENT_FILE, $filename=NULL)
 {
	$id 			=  isset($row->id) ? $row->id : 0;
	if (!$id) {
		// New item, no image yet, get off here
		return false;
		}
	$base_path 		= JPATH_ROOT;
	$db 			= JFactory::getDBO();
	$params 		= JComponentHelper::getParams($component) ;	
    $user 			= JFactory::getUser();	
	$user_id 		= (isset($row->uid)) ? $row->uid : 0;
	$year 			= date("Y");
	$base_dir 		= $base_path . self::getImageDirforUser($user_id, 0, $section);
	$file 			= isset($filename) ? $filename : $row->it_image;
	$path_parts 	= pathinfo($file);
	$file_name 		= $path_parts['filename'];
	$file_ext 		= (isset($path_parts['extension'])) ? strtolower($path_parts['extension']) : '';
	$gdlib 			= gd_info();
	
	if (strlen(trim($params->get('watermark'))) < 1) {
		// No watermark configured, we are done.
		return false;
		}

	if (!file_exists($base_dir.$file_name.'.'.$file_ext)) {
		// File does not exist, we are done.
		return false;	
		}

	// Is it really necessary to create a new image?
	if ($gdlib['PNG Support']) {
		// GD lib is present, carry on	
		if (file_exists($base_dir.'thumbnails'.DS.'stamp_'.$file_name.'.'.$file_ext)) {	
			if (isset($row->it_watermark) && $row->it_watermark == 0) {
				// Watermark is deactivated
				return false;
				} else {
				// file exists, we are done
				return true;				
				} // end if row->watermark
			} // end if file_exists...		
		} else {
		// GD lib not present, no way to create a watermark
		return false;	
		} // end if gdlib...
	
	// Load image
    switch ($file_ext) {
        case "jpg":  $im = imagecreatefromjpeg($base_dir.$file_name.".".$file_ext); break;
        case "jpeg": $im = imagecreatefromjpeg($base_dir.$file_name.".".$file_ext); break;		
        case "gif":  $im = imagecreatefromgif($base_dir.$file_name.".".$file_ext); break;
        case "png":  $im = imagecreatefrompng($base_dir.$file_name.".".$file_ext); break;
        case "bmp":  $im = imagecreatefromwbmp($base_dir.$file_name.".".$file_ext); break;		
		default:
		return false;
		break;
		}
	
	// creating a watermark stamp with GD library
	$stamp = imagecreatetruecolor(300, 80);
	$transparent = imagecolorallocatealpha($im, 0, 0, 0, 127); 	
	imagefilledrectangle($stamp, 0, 0, 299, 79, 0x0000FF);
	imagefilledrectangle($stamp, 4, 4, 295, 75, 0xFFFFFF);

	imagestring($stamp, 5, 20, 20, '(c) '.$year, 0x0000FF);
	imagestring($stamp, 3, 20, 40, $params->get('watermark'), 0x0000FF);

	// retrieve dimensions, set margins
	$marge_right = 1;
	$marge_bottom = 1;
	$sx = imagesx($stamp);
	$sy = imagesy($stamp);

	// lay a watermark over the image with an appropriate opacity
	imagecopymerge($im, $stamp, imagesx($im)/2 - $sx/2 - $marge_right, imagesy($im)/2 - $sy - $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp), $params->get('watermark_opacity'));

	// store image, flush memory
//	imagepng($im, $base_dir.'thumbnails'.DS.'stamp_'.$file_name.'.'.$file_ext);
	imagejpeg($im, $base_dir.'thumbnails'.DS.'stamp_'.$file_name.'.'.$file_ext);
	imagedestroy($im);
	imagedestroy($stamp);
	
	return true;
	}

		
	/*------------------------------------------------------------------
	url_exists:
	checks whether a url really exists
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/
	function url_exists($url){
		if (!function_exists('curl_init')) {
			// No curl function, return true to avoid program crash
			return true;
			}	
		$c=curl_init();
		curl_setopt($c,CURLOPT_URL,$url);
		curl_setopt($c,CURLOPT_HEADER,1);//get the header
		curl_setopt($c,CURLOPT_NOBODY,1);//and *only* get the header
		curl_setopt($c,CURLOPT_RETURNTRANSFER,1);//get the response as a string from curl_exec(), rather than echoing it
		curl_setopt($c,CURLOPT_FRESH_CONNECT,1);//don't use a cached version of the url
		if(!curl_exec($c)){
			return false;
		}else{
			return true;
		}
	}
	
	/*------------------------------------------------------------------
	is_valid_email:
	checks whether a string is a valid email address
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/
	function is_valid_email($email) {
	  return filter_var($email, FILTER_VALIDATE_EMAIL);
	}


	/*------------------------------------------------------------------
	is_valid_url:
	checks whether a string is a valid url (including international domains)
	Copyright (c) 2014 KISS-Software.de
	-------------------------------------------------------------------*/
	function is_valid_url($text) {
		$allprots 	= array('http', 'https', 'ftp', 'news', 'nntp', 'telnet', 'mailto', 'irc', 'ssh', 'sftp', 'webcal');		
		$pattern = "/\b(?:(?:" . implode("|", $allprots) . "):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
		if(preg_match($pattern, $text)){
			return true;
			} else{
			return false;
			}
	}


	/*------------------------------------------------------------------
	getImageDirforUser:
	Creates a unique_image_directory for a given user
	Copyright (c) 2010 KISS-Software.de
	-------------------------------------------------------------------*/
	function getImageDirforUser($uid=0, $retval=1, $directory) {
	    $db = JFactory::getDBO();
	    $live_site = JURI::root();
	    $base_path = JPATH_ROOT;
	    $user =JFactory::getUser();
	    $user_id = $user->get( 'id' );
		$user_dir = DS . $uid;
		$img_dir = str_replace(DS.DS, DS, "media" . DS . $directory . DS . "images" . DS . "users" . $user_dir . DS);
		$thu_dir = $img_dir . "thumbnails" . DS;
		$vid_dir = $img_dir . "videos" . DS;
		$upl_dir = $img_dir . "uploads" . DS;		
		$real_dir = str_replace(DS.DS, DS, $live_site . DS . $img_dir);

		$base_dir = str_replace(DS.DS, DS, $base_path . DS . $img_dir);
		self::make_user_dir($base_dir);
		$direk = str_replace(DS.DS, DS, $base_path . DS . $thu_dir);
		self::make_user_dir($direk);
		$direk = str_replace(DS.DS, DS, $base_path . DS . $vid_dir);
		self::make_user_dir($direk);
		$direk = str_replace(DS.DS, DS, $base_path . DS . $upl_dir);
		self::make_user_dir($direk);

		if ($retval == 1) {
		    return $real_dir;
		    } else {
		    return str_replace($base_path.DS, "", $base_dir);
		    }
	}

	/*------------------------------------------------------------------
	make_user_dir:
	Makes a user directory and changes the dir and file rights
	Copyright (c) 2011 KISS-Software.de
	-------------------------------------------------------------------*/
	function make_user_dir ($base_dir) {
		if (!is_dir($base_dir)) {
		     if (!JFolder::create($base_dir, 0755)) {
				JError::raiseWarning(
					'SOME_ERROR_CODE',
					'JFolder::create: ' . JText::_('KISS_GENERAL_ERROR_DIRNOTCREATED'),
					'Path: ' . $base_dir
				);
				} else {
				// Try to adjust the file and folder rights
				if (JPath::canChmod($base_dir)) {
					JPath::setPermissions($base_dir, 0644, 0755);					
					} else {
					JError::raiseWarning(
						'SOME_ERROR_CODE',
						'JFolder::create: ' . JText::_('KISS_GENERAL_ERROR_FOLDERPERMISSIONS'),
						'Path: ' . $base_dir
					);
					} // End if JPath::canChmod ...
				} // End if(!JFolder::create ...)
		    } // End if (!is_dir....)		
	} 
	
	/*------------------------------------------------------------------
	bootstrapmodal
	prepares the page for displaying a bootstrap modal window
	Copyright (c) 2014 KISS-Software.de
	-------------------------------------------------------------------*/
	function bootstrapmodal() {
	JHTML::_('bootstrap.modal');
	$bimg  = JHtml::_('image', JURI::root().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-16-cancel.png',JURI::root().'components'.DS.KISS_COMPONENT_JDIR.DS.'assets'.DS.'images'.DS.'icon-16-cancel.png');
	$o = '<div class="modal fade" id="lightbox-basic" tabindex="-1" role="dialog" aria-labelledby="lightbox-basic" aria-hidden="true">
	      <div class="modal-dialog">
		    <div class="modal-content">
			<div class="modal-header btn-primary">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">'.$bimg.'</button>
			  <h4 class="modal-title" id="modalTitle-basic">'.JText::_('KISS_GENERAL_IMAGE').'</h4>
			</div>
			<div class="modal-body" id="lightboxbody-basic">
			</div>
			<div class="modal-footer" id="lightboxfooter-basic">
			      <button type="button" class="btn btn-default" data-dismiss="modal">'.sprintf(JText::_('BUTTON_KISS_CLOSE'),$bimg).'</button>
			</div>
		    </div>
		</div>
	      </div>
	      <div class="modal fade" id="lightbox-large" tabindex="-1" role="dialog" aria-labelledby="lightbox-large" aria-hidden="true">
	      <div class="modal-dialog modal-lg">
		    <div class="modal-content">
			<div class="modal-header btn-primary">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">'.$bimg.'</button>
			  <h4 class="modal-title" id="modalTitle-large">'.JText::_('KISS_GENERAL_IMAGE').'</h4>
			</div>
			<div class="modal-body" id="lightboxbody-large">
			</div>
			<div class="modal-footer" id="lightboxfooter-large">
			      <button type="button" class="btn btn-default" data-dismiss="modal">'.sprintf(JText::_('BUTTON_KISS_CLOSE'),$bimg).'</button>
			</div>
		    </div>
		</div>
	      </div>
	      <div class="modal fade" id="lightbox-small" tabindex="-1" role="dialog" aria-labelledby="lightbox-small" aria-hidden="true">
	      <div class="modal-dialog modal-sm">
		    <div class="modal-content">
			<div class="modal-header btn-info">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">'.$bimg.'</button>
			  <h4 class="modal-title" id="modalTitle-small">'.JText::_('KISS_GENERAL_IMAGE').'</h4>
			</div>
			<div class="modal-body" id="lightboxbody-small">
			</div>
			<div class="modal-footer" id="lightboxfooter-small">
			      <button type="button" class="btn btn-default" data-dismiss="modal">'.sprintf(JText::_('BUTTON_KISS_CLOSE'),$bimg).'</button>
			</div>
		    </div>
		</div>
	      </div>';
	  return $o;
	}

    /*------------------------------------------------------------------
	encrypted:
	Encrypts an expression
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/
	function encrypted($value, $sec = null, $salt='!kQm*fF3pXe1Kbm%9')
	{
		$config = new JConfig();
		$secret = (isset($sec)) ? $salt.$sec : $salt.$config->secret;
		srand((double) microtime() * 1000000); //for sake of MCRYPT_RAND
		// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
		$key = hash('SHA256', $secret, true);
		// Build $iv and $iv_base64.  We use a block size of 128 bits (AES compliant) and CBC mode.  (Note: ECB mode is inadequate as IV is not used.)
		srand(); $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_RAND);
		if (strlen($iv_base64 = rtrim(base64_encode($iv), '=')) != 22) return false;
		// Encrypt $value and an MD5 of $value using $key.  MD5 is fine to use here because it's just to verify successful decryption.
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $value . md5($value), MCRYPT_MODE_CBC, $iv));
		// We're done!
		return $iv_base64 . $encrypted;

//	return strtr(base64_encode($value), '-_~', '+/=');
	}

	/*------------------------------------------------------------------
	decrypted:
	Decrypts an expression which was encrypted by the 'encrypted' function
	Copyright (c) 2013 KISS-Software.de
	-------------------------------------------------------------------*/
	function decrypted($value, $sec = null, $salt='!kQm*fF3pXe1Kbm%9')
	{
		$config = new JConfig();
		$secret = (isset($sec)) ? $salt.$sec : $salt.$config->secret;
		if (strlen(trim($value)) > 0) {		
			// Build a 256-bit $key which is a SHA256 hash of $salt and $password.
			$key = hash('SHA256', $secret, true);
			// Retrieve $iv which is the first 22 characters plus ==, base64_decoded.
			$iv = base64_decode(substr($value, 0, 22) . '==');
			// Remove $iv from $encrypted.
			$value = substr($value, 22);
			// Decrypt the data.  rtrim won't corrupt the data because the last 32 characters are the md5 hash; thus any \0 character has to be padding.
			$decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($value), MCRYPT_MODE_CBC, $iv), "\0\4");
			// Retrieve $hash which is the last 32 characters of $decrypted.
			$hash = substr($decrypted, -32);
			// Remove the last 32 characters from $decrypted.
			$decrypted = substr($decrypted, 0, -32);
			// Integrity check.  If this fails, either the data is corrupted, or the password/salt was incorrect.
			if (md5($decrypted) != $hash) return false;
			} else {
			$decrypted = '';
			}
		 // Return the decrypted value
		 return $decrypted;
	//	return base64_decode(strtr($value,  '+/=', '-_~'));
	}

	/*------------------------------------------------------------------
	smartCopy:
	copys files and directories recursively from source to destination
	Copyright (c) 2012 KISS-Software.de
	# source=file & dest=dir => copy file from source-dir to dest-dir
	# source=file & dest=file / not there yet => copy file from source-dir to dest and overwrite a file there, if present
	# source=dir & dest=dir => copy all content from source to dir
	# source=dir & dest not there yet => copy all content from source to a, yet to be created, dest-dir
	-------------------------------------------------------------------*/
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
    {
        $result=false;
		$path_parts = pathinfo($dest);
		$file_name = $path_parts['filename'];
		$dir_name = $path_parts['dirname'];
		$ext_name = $path_parts['extension'];
		$base_name = $path_parts['basename'];
		
        if (is_file($source)) {
		if ($dest[strlen($dest)-1] == DS) {
			// Destination is a folder
                if (!JFolder::exists($dest)) {
                    JFolder::create($dir_name, $options['folderPermission']); 
					}
				$__dest=$dest.basename($source);
				$result = JFolder::copy($source, $__dest);
            } else {
				// Destination is a file
				if (!JFolder::exists($dir_name)) {
                    JFolder::create($dir_name, $options['folderPermission']);
					}
                $__dest=$dest;
				$result = JFile::copy($source, $__dest);
            }
           
        } elseif(is_dir($source)) {
		
            if ($dest[strlen($dest)-1] == DS) {
                if ($source[strlen($source)-1] == DS) {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    JFolder::create($dest, $options['folderPermission']);
                    chmod($dest,$options['filePermission']);					
                }
            } else {
                if ($source[strlen($source)-1] == DS) {
                    //Copy parent directory with new name and all its content
                    JFolder::create($dest, $options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    JFolder::create($dest, $options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..") {
                    if(!is_dir($source.DS.$file)) {
                        $__dest=$dest.DS.$file;
						} else {
                        $__dest=$dest.DS.$file;
						}
                    $result=self::smartCopy($source.DS.$file, $__dest, $options);
                }
            }
            closedir($dirHandle);           
        } else {
            $result=false;
        }
        return $result;
    }
	
}
?>