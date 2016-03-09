<?php namespace SystemCore\Support;

/**
 * SystemCore\Support\SysStr
 * Handle image/files for rendering in customer browser.
 *
 * @package SystemCore\Support\SysStr
 * @author  Anthony Pillos <dev.anthonypillos@gmail.com>
 * @version v1
**/

class SysStr {

	/**
    * getNumberInAString()
    * Initialize our Class Here for Dependecy Injection
    *
    * @param (string) $string Get number in a string.
    * @return void
    * @access  public
    **/
	public function getNumberInAString($string)
	{
		preg_match_all('!\d+!', $string, $matches);
    	return $matches[0];
	}

	/**
    * removeHiddenCharacterInString()
    * Initialize our Class Here for Dependecy Injection
    *
    * @param (string) $string Get number in a string.
    * @return void
    * @access  public
    **/
	public function removeHiddenCharacterInString($str)
	{
		return preg_replace('/(?!\n)[\p{Cc}]/', '', $str);
	}
}