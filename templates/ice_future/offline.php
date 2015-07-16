<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
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

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Template Style 
$TemplateStyle =  $params->get('TemplateStyle'); 

// Logo 
$logo = '<img src="'. JURI::root() . $params->get('logo') .'" alt="'. $sitename .'" />';



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/jui/css/bootstrap-responsive.css" type="text/css" />
    
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/template.css" type="text/css"  />
</head>

<body class="offline_page">
	
    <div id="content">
        
        <jdoc:include type="message" />

        <div id="logo">
            <p><a href="<?php echo $this->baseurl ?>"><?php echo $logo; ?></a></p>
        </div> 
         
       

        <div id="content_inside">
          
          	 <p class="alert">
				<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
            </p>
        
            <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login" class="form-horizontal">
            	
                <fieldset class="input">
                
                 <div class="control-group">
                  <label class="control-label" for="inputEmail"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
                  <div class="controls">
                    <input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" style="position: relative; font-size: 14px; line-height: 20px; " />
                     </div>  
                  </div>
                  
                  <div class="control-group">
                  <label class="control-label" for="inputEmail"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
                  <div class="controls">
                    <input name="password" id="passwd" type="password" class="inputbox" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" size="18" style="position: relative; font-size: 14px; line-height: 20px; " />
                      </div>
                  </div>
              
              <div class="control-group">
                  <div class="controls">
                    <label for="remember" class="checkbox"> <input type="checkbox" name="remember" value="yes" id="remember" /> <?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>  </label>
                    
                  </div>
                </div>
                
                 <div class="control-group">
                  <div class="controls">
                                      <input type="submit" name="Submit" class="btn btn-inverse"  value="<?php echo JText::_('JLOGIN') ?>" />

                  </div>
                </div>
                
            
                <input type="hidden" name="option" value="com_users" />
                <input type="hidden" name="task" value="user.login" />
                <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
                <?php echo JHtml::_('form.token'); ?>
            </fieldset>
            </form>
            
          
        </div>
            
    </div>
      
    
</body>
</html>
