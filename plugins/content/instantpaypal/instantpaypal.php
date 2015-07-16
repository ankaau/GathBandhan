<?php
/** 
 * @author Joomla! Extensions Store
 * @package INSTANTPAYPAL
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// Check to ensure this file is included in Joomla!
defined ( '_JEXEC' ) or die ( 'Direct Access to this location is not allowed.' );

/**
 * Class plugin
 *
 * @package INSTANTPAYPAL
 * @since 1.0
 */ 
jimport ( 'joomla.plugin.plugin' );
class plgContentInstantPaypal extends JPlugin { 
	private function sendEmailNotify(&$session, &$params, &$mainframe) {
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$index = JRequest::getVar('instantpaypalindex', 0);
		$articleID = JRequest::getVar('articlenamespace', 0);
		$productQty = JRequest::getVar('instantpaypalqty', 1);
		$productName = $session->get('instantpaypal_prodname'.$index, null, 'instantpaypal' . $articleID);
		$productPrice = $session->get('instantpaypal_prodprice'.$index, null, 'instantpaypal' . $articleID) . $params->get('currency_code', 'USD');
		
		// Miniform data
		$customerName = $app->getUserStateFromRequest('instantpaypal_customername', 'instantpaypal_customername', null);
		$customerEmail = $app->getUserStateFromRequest('instantpaypal_customeremail', 'instantpaypal_customeremail', null);
		$customerNote = '<div>Note: ' . $app->getUserStateFromRequest('instantpaypal_customernote', 'instantpaypal_customernote', null) . '</div>';
		
		$mailer = JFactory::getMailer();
		// Build e-mail message format
		$mailer->setSender(array($mainframe->getCfg('mailfrom'), $mainframe->getCfg('fromname')));
		$mailer->setSubject($params->get('email_notify_subject'));
		
		$bodyHeader = $params->get('email_notify_body');
		$message_body = <<<BODY
			<div>$bodyHeader</div>
			<hr/>
			<div>
				<span>$productName: </span><span>$productPrice</span><span> - n.$productQty</span>
			</div>
			<hr/>
			<div>
				<span>Name: {$user->get('name', $customerName)} <br/> Email: {$user->get('email', $customerEmail)}</span>
			</div>
			$customerNote
BODY;
		
		$mailer->setBody($message_body);
		$mailer->IsHTML(true);
		
		// Add recipients
 		$mailer->addRecipient($params->get('email_notify_address'));
		  
		// Send the Mail
		$rs	= $mailer->Send();
	}
	
	
	private function runPlugin($context, &$article, &$params, $page = 0) { 
		// Exclude admin exec and not authorized
		$app = JFactory::getApplication ();
		$user = JFactory::getUser();
		$doc = JFactory::getDocument();
		/* @var $doc JDocumentHtml */
		$docType = $doc->getType();
		
		if ($app->isAdmin () || JRequest::getCmd ( 'task' ) == 'edit' || JRequest::getCmd ( 'layout' ) == 'edit') {
			return;
		}
		
		$session = JFactory::getSession();
		// Is module instance execution?
		$isArticleInstance = (bool)$article->id;
		if(!$isArticleInstance) {
			$DBO = JFactory::getDBO();
			$query = "SELECT MIN(id) FROM #__content";
			$article->id = $DBO->setQuery($query)->loadResult();
		}
		
		// Detect email notify hook
		$sendEmailNotify = JRequest::getVar('instantpaypaltask', false);
		if($sendEmailNotify === 'sendemailnotify' && $this->params->get('email_notify_send', true)) {
			$this->sendEmailNotify($session, $this->params, $app);
			return;
		}
		
		$matches = array ();
		$overrides = array ();
		$btnimg = '';
		$additionalFormHtml = null;
		$uniqueShipping = $this->params->get('global_unique_shipping', 1);
		
		if(!isset($article->text)) {
			$article->text = $article->introtext;
		}
		
		// Check document type
		if (strcmp("html", $docType) != 0) {
			$article->text = preg_replace("/{instantpaypal}(.*?){\/instantpaypal}/i", '', $article->text);
			return;
		}
		// Output JS APP nel Document
		if(JRequest::getCmd('print')) {
			$article->text = preg_replace("/{instantpaypal}(.*?){\/instantpaypal}/i", '', $article->text);
			return;
		}
		
		// Avoid processing if article view is only selected
		if($this->params->get('showonly_viewarticle', 0) && JRequest::getVar('view') != 'article') {
			$article->text = preg_replace("/{instantpaypal}(.*?){\/instantpaypal}/i", '', $article->text );
			return null;
		}
		
		preg_match_all ( '/{instantpaypal}(.*?){\/instantpaypal}/', $article->text, $matches, PREG_PATTERN_ORDER );
		if (count ( $matches [0] )) {
			for($i = 0; $i < count ( $matches [0] ); $i ++) {
				// Reset resources
				$formHtml = null;
				$additionalFormHtml = null;
				$mode = null;
				// Init overrides element analysis
				$overridesArray = array();
				$overrides = strlen(trim($matches [1] [$i]) )? explode ( ",", trim($matches [1] [$i] )) : array(); 
				if(count($overrides)) {
					foreach ($overrides as $overrideParam) {
						$temp = explode ( "=", $overrideParam );
						$left = $temp[0];
						array_shift($temp);
						$right = implode('', $temp);
						$overridesArray[$left] = $right;
					}
				}
				 
				// Init overrides variables with default param fallback
				$action = $originalAction = array_key_exists('action', $overridesArray) ? $overridesArray['action'] : $this->params->get('button_type', 'pay');
				$price = array_key_exists('price', $overridesArray) ? $overridesArray['price'] : $this->params->get('default_price', 0);
				$productName = array_key_exists('productname', $overridesArray) ? $overridesArray['productname'] : $this->params->get('default_productname', 'ProductDemo');
				$showQty = array_key_exists('showquantity', $overridesArray) ? $overridesArray['showquantity'] : $this->params->get('global_showquantity', false);
				// Tax vars
				$taxAmount = array_key_exists('taxamount', $overridesArray) ? $overridesArray['taxamount'] : $this->params->get('global_taxamount', 0);
				$taxText = array_key_exists('taxtext', $overridesArray) ? $overridesArray['taxtext'] : $this->params->get('global_taxtext', 'Tax +');
				$taxType = array_key_exists('taxtype', $overridesArray) ? $overridesArray['taxtype'] : $this->params->get('global_taxtype', 'fixed');
				
				// Shipping vars
				$shippingAmount = array_key_exists('shippingamount', $overridesArray) ? $overridesArray['shippingamount'] : $this->params->get('global_shippingamount', 0);
				$shippingText = array_key_exists('shippingtext', $overridesArray) ? $overridesArray['shippingtext'] : $this->params->get('global_shippingtext', 'Shipping +');
				$shippingType = array_key_exists('shippingtype', $overridesArray) ? $overridesArray['shippingtype'] : $this->params->get('global_shippingtype', 'single');
				
				// Returning pages
				$returningProductPage = array_key_exists('returnurl', $overridesArray) ? $overridesArray['returnurl'] : $this->params->get('return_url', false);
				
				// Target window on floating type
				$floatingTarget = null;
				if($this->params->get ( 'open_window', '_blank' ) === '_floating') {
					$widthXfloating = $this->params->get('floating_width', 960);
					$heightXfloating = $this->params->get('floating_height', 480);
					$floatingTarget = 'onSubmit="window.open(\'\', \'_floating\', \'width=' . $widthXfloating .'px,height=' . $heightXfloating . 'px\')"';
				}
				// TYPE DONATE
				if (strtolower ( $action ) == "donate") {
					$action = '_donations';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_donate' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				} else if (preg_match('/cart/i', $action)) { // TYPE CART 
					// Setting dell'add mode to cart
					$mode = '<input type="hidden" name="add" value="1" />';
					
				 	if (strtolower ( $action ) == "fullcart") {
						$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_cart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimgview = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_viewcart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
					} else if (strtolower ( $action ) == "addtocart") {
						$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_cart' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimgview = ''; 
					} else if (strtolower ( $action ) == "showcart") {
						$btnimgview = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_viewcart' .  $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
						$btnimg = ''; 
					}
					
					if (strtolower ( $action ) == "fullcart" || strtolower ( $action ) == "showcart") { 
						// view button
						$additionalFormHtml = 	'<form style="margin-top: 10px" class="subform ' . $this->params->get ( 'css_form_class', '' ) . '" name="instantpaypal" action="https://www.paypal.com/cgi-bin/webscr" method="post" ' . $floatingTarget . ' target="' . $this->params->get ( 'open_window', '_blank' ) . '"> 
													<input type="hidden" name="business" value="' . $this->params->get ( 'paypal_email', '' ) . '" />  
													<input type="hidden" name="cmd" value="_cart" /> 
													<input type="hidden" name="display" value="1" />
													<input type="hidden" name="lc" value="' . $this->params->get ( 'country_code', 'US' ) . '" />
				                 					<input type="hidden" name="charset" value="utf-8" />
													<input type="image" name="submit" style="border: 0;" src="' . $btnimgview . '" alt="PayPal - The safer, easier way to pay online" /> 
												</form>'; 
					} 
					// Override cmd paypal
					$action = '_cart';
				} else if (strtolower ( $action ) == "pay") { // TYPE PAY
					$action = '_xclick';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_paynow' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				} else { // DEFAULT TYPE XCLICK
					$action = '_xclick';
					$btnimg = 'https://www.paypal.com/' . $this->params->get ( 'button_path', 'en_US' ) . '/i/btn/btn_buynow' . $this->params->get ( 'default_btnsize', '_SM' ) . '.gif';
				}

				// Customer mini form info
				$customerSessionName = $app->getUserState('instantpaypal_customername') || $user->name;
				$customerSessionEmail = $app->getUserState('instantpaypal_customeremail') || $user->email;
				$bitMask = ($this->params->get('showinput_name', false) & !$customerSessionName) | ($this->params->get('showinput_email', false) & !$customerSessionEmail) | $this->params->get('showinput_note', false);
				if($this->params->get('showinput_miniform', false) && $bitMask) {
					$formHtml .= '<style type="text/css">fieldset.info {
																width: 30%;
																border-top: 1px solid #CCC !important;
																padding: 2px 2px 5px 2px;
															}
														 fieldset.info legend {
																font-size: 12px;
															}
														 fieldset.info label {
																font-size: 11px;
																width: 35px;
																display: inline-block;
															}
							</style>';
					$formHtml .= '<fieldset class="info"><legend>Info</legend>';
						
					if($this->params->get('showinput_name', false) && !$user->id && !$customerSessionName) {
						$requiredName = (int)$this->params->get('showinput_name') == 3 ? 'class="required"' : '';
						$requiredNameSign = $requiredName ? '*' : '';
						$formHtml .= '<div><label style="min-width:50px">Name ' . $requiredNameSign . '</label><input type="text" ' . $requiredName . ' data-role="infominiform" data-name="Name" name="instantpaypal_customername" value=""/></div>';
					}
					if($this->params->get('showinput_email', false) && !$user->id && !$customerSessionEmail) {
						$requiredEmail = (int)$this->params->get('showinput_email') == 3 ? 'class="required"' : '';
						$requiredEmailSign = $requiredEmail ? '*' : '';
						$formHtml .= '<div><label style="min-width:50px">Email ' . $requiredEmailSign . '</label><input type="text" ' . $requiredEmail . ' data-role="infominiform" data-name="Email" name="instantpaypal_customeremail" value=""/></div>';
					}
						
					if($this->params->get('showinput_note', false)) {
						$formHtml .= '<div><label style="min-width:50px">Note</label><input type="text" data-role="infominiform" name="instantpaypal_customernote" value=""/></div>';
					}
					$formHtml .= '</fieldset>';
				}
				
				$formActionPP =  $this->params->get ( 'sandbox_mode', 0) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';
				$formHtml .= '<form class="' . $this->params->get ( 'css_form_class', '' ) . '" name="instantpaypal" action="' . $formActionPP . '" method="post" ' . $floatingTarget . ' target="' . $this->params->get ( 'open_window', '_blank' ) . '">
							  	<input type="hidden" name="business" value="' . $this->params->get ( 'paypal_email', '' ) . '" />
							  	<input type="hidden" name="cmd" value="' . $action . '" />
					  			<input type="hidden" name="amount" value="' . $price . '" />
							  	<input type="hidden" name="item_name" value="' . $productName . '" />' .
							  	$mode .
							  	'<input type="hidden" name="currency_code" value="' . $this->params->get ( 'currency_code', 'USD' ) . '" /> 
							 	<input type="hidden" name="lc" value="' . $this->params->get ( 'country_code', 'US' ) . '" />
                				<input type="hidden" name="charset" value="utf-8" />';
				
				
				if($this->params->get('auto_url', 1)) {
					$uriInstance = JURI::getInstance();
					$formHtml .= '<input type="hidden" name="return" value="' . $uriInstance->toString() . '" />';
					$formHtml .= '<input type="hidden" name="cancel_return" value="' .  $uriInstance->toString() . '" />';
				} else {
					if ($returningProductPage) { // hint, return url
						$formHtml .= '<input type="hidden" name="return" value="' . $returningProductPage . '" />';
					}
					
					if ($cancel_url = $this->params->get('cancel_url', false)) { // hint, return url
						$formHtml .= '<input type="hidden" name="cancel_return" value="' . $cancel_url . '" />';
					} 
				}
				
				if($taxAmount && $action != '_donations') {
					$taxFormType = $taxType == 'fixed' ? 'tax' : 'tax_rate';
					$formHtml .= '<input type="hidden" name="' . $taxFormType . '" value="' . $taxAmount . '" />';
				}
				
				if($shippingAmount && $action != '_donations') {
					switch ($shippingType) {
						case 'single':
							$formHtml .= '<input type="hidden" id="shippingfield' . $i . '" name="shipping" value="' . $shippingAmount . '" />';
							break;
								
						case 'multiple':
							$formHtml .= '<input type="hidden" id="shippingfield' . $i . '" name="shipping" value="' . $shippingAmount . '" />';
							$formHtml .= '<input type="hidden" name="shipping2" value="' . $shippingAmount . '" />';
							break;
					}
				}
				
				$formHtml .= '<input type="image" onclick="sendEmailIframe(event, ' . $article->id . ', ' . $i .');" name="submit" style="border: 0;" src="' . $btnimg . '" alt="PayPal - The safer, easier way to pay online" />';
				
				if($showQty && $action != '_donations') {
					$formHtml .= '<div>' . $this->params->get('quantity_text', 'Quantity:') . '<input type="text" id="quantityfield' . $i . '" name="quantity" size="2" style="max-width:30px;" value="1"/></div>';
				}
				 
				$formHtml .= '</form>';
					 
				$additionalInfo = null;
				if($this->params->get('showxtdinfo', 1)) {
					$currencyOrPercentage = $taxType == 'fixed' ? $this->params->get('currency_code', 'USD') : '%';
					$taxAmountString =  $taxAmount ? ' | <span>' . $taxText . $taxAmount . ' ' . $currencyOrPercentage . '</span>' : '';
					$shippingAmountString =  $shippingAmount ? ' | <span>' . $shippingText . $shippingAmount . ' ' . $this->params->get('currency_code', 'USD') . '</span>' : '';
					$additionalInfo = '<div class="' . $this->params->get('css_infoxtd_class', null) . '"><span>' . $productName . '</span> | <span>' . $price . ' ' . $this->params->get('currency_code', 'USD') . '</span>' . $taxAmountString . $shippingAmountString .'</div>'; 
				}
				  
				// Final show forms logic
				$finalForms = strtolower ( $originalAction ) != "showcart" ? $formHtml . $additionalInfo . $additionalFormHtml : $additionalFormHtml;
				// Replace unique per firm instance
				$instance = $matches [1] [$i];
				$article->text = $article->introtext = str_replace("{instantpaypal}$instance{/instantpaypal}", $finalForms, $article->text );
				
				// Put info on session for email notify later
				$session->set('instantpaypal_prodname'.$i, $productName, 'instantpaypal' . $article->id);
				$session->set('instantpaypal_prodprice'.$i, $price, 'instantpaypal' . $article->id); 
			}
			// Queue JS code
			if(!defined('INSTPP_IFRAME_JSINCLUDED')) {
				$jsCode = <<<JSCODE
								<script>
								//<![CDATA[
									var sendEmailIframe = function(eventObject, articleID, productIterationID) {
										// Try to get qty
										var qty = document.getElementById('quantityfield' + productIterationID);
										var shipping = document.getElementById('shippingfield' + productIterationID);
										var qtyAmount = '';
										var miniFormQueryString = '';
										var elements2Remove = new Array();
										if(qty) {
											qtyAmount = '&instantpaypalqty=' + qty.value;
										}
										
										// Manage unique cart shipping
										if(window.sessionStorage && $uniqueShipping) {
											if(window.sessionStorage.getItem('cart_shipping') == 1) {
												var node = document.getElementById('shippingfield' + productIterationID);
												node.parentNode.removeChild(node);
											}
											if(shipping) {
												window.sessionStorage.setItem('cart_shipping', 1);
											}
										}
			
										// Supports IE8+
										if (document.querySelectorAll) {
											var normalizedTarget = (eventObject.currentTarget) ? eventObject.currentTarget : eventObject.srcElement;
											var targetFieldset = normalizedTarget.parentNode.previousSibling;
											if(targetFieldset.className == 'info') {
												var miniFormFields = targetFieldset.querySelectorAll('input[data-role=infominiform]');
												for(var i=0; i<miniFormFields.length; i++) {
													if(miniFormFields[i].className == 'required' && !miniFormFields[i].value) {
														alert(miniFormFields[i].getAttribute('data-name') + ' required');
														if(eventObject.preventDefault) {
															eventObject.preventDefault();
														} else {
															eventObject.returnValue = false;
														}
														return false;
													}
													// Email validation
													if(miniFormFields[i].getAttribute('data-name') == 'Email' && miniFormFields[i].value) {
														var emailRE = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
														if(!emailRE.test(miniFormFields[i].value)) {
															alert(miniFormFields[i].getAttribute('data-name') + ' no valid');
															if(eventObject.preventDefault) {
																eventObject.preventDefault();
															} else {
																eventObject.returnValue = false;
															}
															return false;
														}
													}
			
													if(miniFormFields[i].value) {
														miniFormQueryString += '&' + miniFormFields[i].name + '=' + encodeURIComponent(miniFormFields[i].value);
			
														// Try to collect all aready empty siblings fields
														if(miniFormFields[i].name != 'instantpaypal_customernote') {
															var namesToRemove = document.querySelectorAll('input[name=' + miniFormFields[i].name + ']');
															if(namesToRemove.length) {
																for(var n=0; n<namesToRemove.length; n++) {
																	elements2Remove.push(namesToRemove[n]);
																}
															}
														}
													}
												}
											}
											// Try to remove all already empty siblings fields
											if(elements2Remove.length) {
												for(var k=0; k<elements2Remove.length; k++) {
													var element2Remove = elements2Remove[k];
													element2Remove.parentNode.removeChild(element2Remove.previousSibling);
													element2Remove.parentNode.removeChild(element2Remove);
												}
											}
										}
			
										// Try to get info miniform fields
									 	iframe = document.createElement("IFRAME");
									    iframe.setAttribute("src","index.php?option=com_content&view=article&id=" + articleID + "&articlenamespace=" + articleID + "&instantpaypaltask=sendemailnotify&instantpaypalindex=" + productIterationID + qtyAmount + miniFormQueryString);
										iframe.setAttribute("width","0");
										iframe.setAttribute("height","0");
									   	document.getElementsByTagName("body")[0].appendChild(iframe);
									}
								//]]>
								</script>
JSCODE;
			$article->text .= $jsCode;
			$article->introtext = $article->text;
			define('INSTPP_IFRAME_JSINCLUDED', 1);
			}
		}
		return null;
	}
	
	/**
	 * onContentPrepare handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		$app = JFactory::getApplication();
		if(!$app->isAdmin() && $this->params->get('includeevent', 'onContentAfterDisplay') == 'onContentPrepare') {
			$this->runPlugin($context, $article, $params, $page = 0);
		}
	}
	
	/**
	 * onContentAfterDisplay handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onContentAfterDisplay($context, &$article, &$params, $page = 0) {
		$app = JFactory::getApplication();
		if(!$app->isAdmin() && $this->params->get('includeevent', 'onContentAfterDisplay') == 'onContentAfterDisplay') {
			$this->runPlugin($context, $article, $params, $page = 0);
		}
	}
}