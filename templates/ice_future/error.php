<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

// Template Style 
$TemplateStyle =  $params->get('TemplateStyle'); 
$sitename="gathbandhanpyaarka";
// Logo 
$logo = '<img src="'. JURI::root() . $params->get('logo') .'" alt="'. $sitename .'" />';

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->title; ?> <?php echo $this->error->getMessage();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo $this->language; ?>" />
    
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-responsive.css" type="text/css" />
    
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css" />


	
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="error_page">
	
	<div class="container clearfix">
      
      	<div class="row">
        	
             <div class="span4" id="logo">	
                <p><a href="<?php echo $this->baseurl ?>"><?php echo $logo; ?></a></p>
             </div>
              
             <div id="content" class="span8">
                
                <h1 class="page-header"><?php echo JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'); ?></h1>
              
                 <p class="alert"><?php echo $this->title; ?> - <?php echo $this->error->getMessage();?></p>
                   
                  <p><?php echo JText::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?></p>
                  
                  <p><a href="<?php echo $this->baseurl; ?>" class="btn"><i class="icon-home"></i> <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a></p>
                                        
            </div>                            
      
   		</div>
           
	</div>
	
</body>
</html>
