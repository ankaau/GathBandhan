/**
 * @package Module Smart Countdown for Joomla! 1.7 - 2.5
 * @version 1.0: plural_ru-ru.js
 * @author Alex Polonski
 * @copyright (C) 2012 - Alex Polonski
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
function mod_scdpro_plural(n) {
	var rest10 = n % 10;
	var rest100 = n % 100;
	if(rest10 == 1 && rest100 != 11) {
		return '_1';
	} else if(rest10 >=2 && rest10 <= 4 && (rest100 < 10 || rest100 >= 20)) {
		return '_2';
	} else {
		return '';
	}
}
