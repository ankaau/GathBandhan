<?php
defined('JPATH_PLATFORM') or die;

class JFormRuleDigitsXML extends JFormRule
{
	/**
	 * Method to test the value.
	 *
	 * @param   JXMLElement  &$element  The JXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed        $value     The form field value to validate.
	 * @param   string       $group     The field name group control value. This acts as as an array container for the field.
	 *                                  For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                  full field name would end up being "bar[foo]".
	 * @param   JRegistry    &$input    An optional JRegistry object with the entire data set to validate against the entire form.
	 * @param   object       &$form     The form object for which the field is being tested.
	 *
	 * @return  boolean  True if the value is valid, false otherwise.
	 *
	 * @since   11.1
	 * @throws  JException on invalid rule.
	 */
	
	public function test(&$element, $value, $group = null, &$input = null, &$form = null)
	{
		if(empty($value)) {
			return true;
		}
		
		// compatibility workaround: in some environments XML document is
		// saved with all "<styles>" element opening tags replaced with
		// "<s-tyles>". We allow this replace (as it can be DB security
		// concerned: looks like an HTML element <style...) and do not
		// report error. Later the original element syntax will be restored 
		$value = str_replace('<s-tyles>', '<styles>', $value);
		
		// now XML document should be valid
		jimport('joomla.utilities.xmlelement');
		libxml_use_internal_errors(true);
		$xml = simplexml_load_string($value, 'JXMLElement');
		
		if($xml === false) {
			$errors = array(JText::_('MOD_SMARTCDPRO_ERROR_DIGITS_XML_ERROR'));
			foreach (libxml_get_errors() as $error) {
				$errors[] =  'XML: '.$error->message;
			}
			$element['message'] = implode('<br />', $errors);
			return false;
		}
		
		$field = $xml->getName();
		
		if($field != 'config') {
			$element['message'] = JText::_('MOD_SMARTCDPRO_ERROR_DIGITS_XML_CONFIG_MISSING');
			return false;
			
		}
		if(!($xml->xpath('//digit[@scope="*"]'))) {
			$element['message'] = JText::_('MOD_SMARTCDPRO_ERROR_DIGITS_XML_DEFAULT_DIGIT_MISSING');
			return false;
			
		}
		$element['message'] = '';
		
		return true;
	}
}
