<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Alexander Kellner <alexander.kellner@einpraegsam.net>
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


/**
 * Plugin 'user_email2powermail_userfuncs.php' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage user_email2powermail_userfuncs.php
 */
class user_email2powermail_userfuncs extends tslib_pibase {

	var $languid = 0; // FE Language

	/**
	 * Function replace() is str_replace for typoscript
	 *
	 * @param	string		$content: Empty
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	string		$content
	 */
	function replace($content = '', $conf = array()) {
		// config
		global $TSFE;
    	$this->cObj = $TSFE->cObj; // cObject
		
		// Let's go
		$search = $this->cObj->cObjGetSingle($conf['userFunc.']['search'], $conf['userFunc.']['search.']); // searchstring
		$replace = $this->cObj->cObjGetSingle($conf['userFunc.']['replace'], $conf['userFunc.']['replace.']); // replacestring
		$content = $this->cObj->cObjGetSingle($conf['userFunc.']['content'], $conf['userFunc.']['content.']); // contentstring
		
		if ($search == '') return 'ERROR in user_email2powermail_userfuncs->replace: No search variable given!'; // error if no search string given
		return str_replace($search, $replace, $content); // return content
	}


	/**
	 * Function email2name() returns name to email (from table tx_email2powermail_receivers)
	 *
	 * @param	string		$content: Empty
	 * @param	array		$conf: The PlugIn Configuration
	 * @return	string		$name 
	 */
	function email2name($content = '', $conf = array()) {
		// config
		global $TSFE;
    	$this->cObj = $TSFE->cObj; // cObject
		$this->conf = $conf['userFunc.']; // ts configuration
		$input = $this->cObj->cObjGetSingle($this->conf['input'], $this->conf['input.']); // uid of tabledate or email address
		$this->languid = $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] ? $GLOBALS['TSFE']->tmpl->setup['config.']['sys_language_uid'] : 0; // current language uid
		
		
		// Let's go
		// 1. give me the needed email
		if ($input == '') { // if input is empty
		
			return 'ERROR in user_email2powermail_userfuncs->email2name: No input variable given!'; // error if no input given
		
		} elseif (is_numeric($input)) { // input variable is a uid
		
			// get email to uid
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ( // DB query
				'email',
				'tx_email2powermail_emailcache',
				$where_clause = 'tx_email2powermail_emailcache.uid = ' . intval($input) . tslib_cObj::enableFields('tx_email2powermail_emailcache'),
				$groupBy = '',
				$orderBy = '',
				$limit = 1
			);
			if ($res) $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res); // Result in array
			if (t3lib_div::validEmail($row['email'])) {
				$email = $row['email']; // get email
			}
			
		} elseif (t3lib_div::validEmail($input)) { // input variable is email
		
			$email = $input; // input is email so take this
			
		} else {
			return 'ERROR in user_email2powermail_userfuncs->email2name: Input variable is not uid and no email address!'; // error if input has wrong type
		}
		
		
		// 2. give me the name to the email address
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ( // DB query
			'uid,pid,name',
			'tx_email2powermail_receivers',
			$where_clause = 'tx_email2powermail_receivers.email = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($email, 'tx_email2powermail_receivers') . tslib_cObj::enableFields('tx_email2powermail_receivers'),
			$groupBy = '',
			$orderBy = '',
			$limit = 1
		);
		if ($res) $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res); // Result in array
		$loc_arr = array(
			'pid' => $row['pid'], 
			'uid' => $row['uid'], 
			'name' => $row['name']
		);
		$tmp_row = $GLOBALS['TSFE']->sys_page->getRecordOverlay('tx_email2powermail_receivers', $loc_arr, $this->languid, ''); // language overlay for description only
		
		if ($tmp_row['name']) return t3lib_div::removeXSS($tmp_row['name']);
	}
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/user_email2powermail_userfuncs.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/user_email2powermail_userfuncs.php']);
}

?>