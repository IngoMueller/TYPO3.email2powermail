<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Alex Kellner <alexander.kellner@einpraegsam.net>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

require_once(PATH_tslib . 'class.tslib_pibase.php');
require_once(t3lib_extMgm::extPath('email2powermail') . 'res/class.tx_email2powermail_div.php'); // load div class


/**
 * Plugin 'tx_email2powermail_replace' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage tx_email2powermail_replace
 */
class tx_email2powermail_replace extends tslib_pibase {
	
	public $prefixId      = 'tx_email2powermail';
	public $scriptRelPath = 'res/class.tx_email2powermail_replace.php'; // Path to this script relative to the extension dir.
	public $extKey        = 'email2powermail'; // The extension key.

	private $regEx_email = '#[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z.-]+\.[a-z]{2,}#i'; // RegEx to get email addresses in FE
	
	
	/**
	 * Main function to rewrite the content
	 *
	 * @param	string		$content: Content Text
	 * @param	array		$replace: Array with replace information
	 * @param	array		$pObj: parent Object
	 * @return	void		
	 */
	public function main(&$content, $replace, &$pObj) {
		// config
		global $TSFE;
    	$local_cObj = $TSFE->cObj; // cObject
		$this->content = &$content; // make content global
		$this->conf = $pObj->conf; // get TS
		$this->cObj = $pObj->cObj; // contentObject
		
		// Let's go
		// 1. change links
		foreach ((array) $replace as $key => $value) { // one loop for every replace item on current page
			$local_cObj->start(array('uid' => $value), 'tt_content');
			$tmp_link = $this->cObj->cObjGetSingle($this->conf['link'], $this->conf['link.']); // create link with TS
			$this->content = str_replace($key, $tmp_link, $this->content); // replace each email with link within content
		}
		
		// 2. change linktext
		preg_match_all($this->regEx_email, $this->content, $result);
		foreach ((array) $result[0] as $key => $value) { // one loop for every email
			$tmp_text = $this->cObj->cObjGetSingle($this->conf['text'], $this->conf['text.']); // create text with TS
			if (strlen($tmp_text) > 0) { // only if there is a new text
				$this->content = str_replace($value, $tmp_text, $this->content); // replace text
			}
		}
	}
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_replace.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_replace.php']);
}

?>