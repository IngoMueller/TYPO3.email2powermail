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

/**
 * Plugin 'tx_email2powermail_div' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage tx_email2powermail_div
 */
class tx_email2powermail_div {
	
	/**
	 * Remove "mailto:" from link
	 *
	 * @param	string		$str: a link like "mailto:email@email.de"
	 * @return	string		$str: without "mailto:"
	 */
	public function removeMailto($str) {
		return str_replace('mailto:', '', $str); // return string without "mailto:"
	}
	
	
	/**
	 * Check if current page is allowed to change the email links
	 *
	 * @param	array		$pObj: Parent Object
	 * @return	boolean 
	 */
	public function allowed($pObj) {
		// config
		$this->conf = $pObj->conf; // make config available
		$allow = 0; // disallow rewriting at the beginning
		
		// let's go
		$tree = t3lib_div::makeInstance('t3lib_queryGenerator'); // make instance for query generator class
		if ($this->conf['allowedPages'] != 0) { // activate only if allowedPages is not 0
			
			// 1. Page Check (allow only if current page is in list)
			$allowedPages = t3lib_div::trimExplode(',', $this->conf['allowedPages'], 1); // array with allowed pages
			for ($i=0; $i<count($allowedPages); $i++) { // one loop for every allowed parent page
				$cur_pids = $tree->getTreeList($allowedPages[$i], 99, 0, 1); // get list of pages from current allowedpage recursive down
				$cur_pids_arr = t3lib_div::trimExplode(',', $cur_pids, 1); // array with current allowed pages
				if (in_array($GLOBALS['TSFE']->id, $cur_pids_arr)) { // if current page is in the current allowed pages
					$allow = 1; // allow rewriting
				}
			}
			
			// 2. Usergroup Check (disallow if current user is in usergroup deaktivate list)
			if ($GLOBALS['TSFE']->fe_user->user['uid'] > 0 && $this->conf['disallow4Usergroup'] != '') { // if a user is logged in and disallow4Usergroup is activated
				$groupArrayConf = t3lib_div::trimExplode(',', $this->conf['disallow4Usergroup'], 1); // array with all disabled groups
				$groupArray = t3lib_div::trimExplode(',', $GLOBALS['TSFE']->fe_user->user['usergroup'], 1); // array with all groups from currently logged in user
				if (count(array_intersect($groupArray, $groupArrayConf)) > 0) { // if there are some of the groups where the rewrite plugin should be disabled
					$allow = 0; //  disallow rewriting
				}
			}
			
			// 3. User Check (disallow if current user is in deactivate list)
			if ($GLOBALS['TSFE']->fe_user->user['uid'] > 0 && $this->conf['disallow4User'] != '') { // if a user is logged in and disallow4User is activated
				if (t3lib_div::inList($this->conf['disallow4User'], $GLOBALS['TSFE']->fe_user->user['uid'])) { // if current logged in user is in deactivate list
					$allow = 0; // disallow rewriting
				}
			}
		}
		
		return $allow; // return if it's allowed to rewrite 0/1
	}
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_div.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_div.php']);
}

?>