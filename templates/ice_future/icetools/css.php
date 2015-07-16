<?php
//  @copyright	Copyright (C) 2012 IceTheme. All Rights Reserved
//  @license	Copyrighted Commercial Software 
//  @author     IceTheme (icetheme.com)

// No direct access.
defined('_JEXEC') or die;


//////////////////////////////////////  CSS  //////////////////////////////////////

// Twitter bootstrap
$doc->addStyleSheet($this->baseurl. '/media/jui/css/bootstrap.css');

if ($this->params->get('responsive_template')) {
	$doc->addStyleSheet($this->baseurl. '/media/jui/css/bootstrap-responsive.css');
} 
 
// CSS by IceTheme for this Tempalte
$doc->addStyleSheet($this->baseurl. '/templates/' .$this->template. '/css/joomla.css');
$doc->addStyleSheet($this->baseurl. '/templates/' .$this->template. '/css/template.css');

if ($this->params->get('responsive_template')) { 
$doc->addStyleSheet($this->baseurl. '/templates/' .$this->template. '/css/responsive.css');
}


// Adjusting columns width
if ($this->countModules('left and right'))
{
	$colspan = "span6";
}
elseif ($this->countModules('left or right'))
{
	$colspan = "span9";
}
else
{
	$colspan = "span12";
}


// Adjusting promo width
if ($this->countModules('promo1 and promo2 and promo3 and promo4'))
{
	$promospan = "span3";
}
elseif ($this->countModules('promo1 and promo2 and promo3'))
{
	$promospan = "span4";
}
elseif ($this->countModules('promo1 and promo2'))
{
	$promospan = "span6";
}
else
{
	$promospan = "span12";
}

// Adjusting footer width
if ($this->countModules('footer1 and footer2 and footer3 and footer4'))
{
	$footerspan = "span3";
}
elseif ($this->countModules('footer1 and footer2 and footer3'))
{
	$footerspan = "span4";
}
elseif ($this->countModules('footer1 and footer2'))
{
	$footerspan = "span6";
}
else
{
	$footerspan = "span12";
}


?>


<style type="text/css" media="screen">

<?php if (!$this->countModules('marketing')) { ?>
#main {
	border-bottom: 1px solid #ccc;
	box-shadow: 0  1px 0 #fff;}	
	

<?php } ?> 				

</style>


<!-- Google Fonts -->
<link href='http://fonts.googleapis.com/css?family=Coming+Soon|Open+Sans' rel='stylesheet' type='text/css'>

<link id="stylesheet" rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/styles/<?php echo $templatestyle; ?>.css" />

<?php  if ($this->params->get('responsive_template')) { ?>
<!-- Template Styles -->
<link id="stylesheet-responsive" rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/styles/<?php echo $templatestyle; ?>_responsive.css" />
<?php } ?>

<link id="stylesheet-custom" rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/custom.css" />




<!--[if lte IE 8]>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/ie8.css" />
<![endif]-->

<!--[if lte IE 9]>
<style type="text/css" media="screen">

</style>	
<![endif]-->


<!--[if lt IE 9]>
    <script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
<![endif]-->
