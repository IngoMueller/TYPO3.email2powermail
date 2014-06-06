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
require_once(t3lib_extMgm::extPath('email2powermail') . 'res/class.tx_email2powermail_cache.php'); // load cache class
require_once(t3lib_extMgm::extPath('email2powermail') . 'res/class.tx_email2powermail_replace.php'); // load replace class
require_once(t3lib_extMgm::extPath('email2powermail') . 'res/class.tx_email2powermail_div.php'); // load div class


/**
 * Plugin 'tx_email2powermail_fe' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage tx_email2powermail_fe
 */
class tx_email2powermail_fe extends tslib_pibase {
	
	public $prefixId      = 'tx_email2powermail';
	public $scriptRelPath = 'res/class.tx_email2powermail_fe.php'; // Path to this script relative to the extension dir.
	public $extKey        = 'email2powermail'; // The extension key.
	
	private $regEx_email_link = '#mailto:[a-z0-9\-_]?[a-z0-9.\-_]+[a-z0-9\-_]?@[a-z.-]+\.[a-z]{2,}#i'; // RegEx to get email address links in FE
	private $replace = array();
	
	
	/**
	 * Main function for FE manipulation
	 *
	 * @param	array		$params: parent object
	 * @param	array		$that:
	 * @return	void
	 */
	public function main(&$params, &$that) {
		// config
		$this->content = &$params['pObj']->content; // make content global
		$this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_email2powermail.']; // get configuration
		$this->cache = t3lib_div::makeInstance('tx_email2powermail_cache'); // get cache methods
		$this->replacer = t3lib_div::makeInstance('tx_email2powermail_replace'); // get replace methods
		$this->div = t3lib_div::makeInstance('tx_email2powermail_div'); // get div methods
		$this->cObj = $that->cObj;
		
		// Let's go
		if ($this->div->allowed($this)) { // if current page and current user is allowed to see rewritten email links
			
			preg_match_all($this->regEx_email_link, $this->content, $result); // get all email links (mailto:email@email.org) of current FE string
			
			for ($i=0; $i<count($result[0]); $i++) { // one loop for every email link in FE
				if (t3lib_div::validEmail($this->div->removeMailto($result[0][$i]))) { // if it's a correct email
					$uid = $this->cache->main($result[0][$i], $this); // open cache methods
					
					$this->replace[$result[0][$i]] = $uid; // create replace array: 254 => 'mailto:email@email.de'
				}
			}
			$this->replacer->main($this->content, $this->replace, $this); // rewrite content
			
		}
	}
	
	
	/**
	 * Preflight function for noCache page generating
	 *
	 * @param	array		$params: parent object
	 * @param	array		$that:
	 * @return	void
	 */
	public function noCache(&$params, &$that) {
		if (!$GLOBALS['TSFE']->isINTincScript()) { // If there are no INTincScripts to include
			return; // stop
		} 
		$this->main($params, $that); // call main replace function
	}
	
	
	/**
	 * Preflight function for Cache page generating
	 *
	 * @param	array		$params: parent object
	 * @param	array		$that:
	 * @return	void
	 */
	public function cache(&$params, &$that) {
		if ($GLOBALS['TSFE']->isINTincScript()) { // If there are any INTincScripts to include
			return; // stop
		} 
		$this->main($params, $that); // call main replace function
	}
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_fe.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_fe.php']);
}

?>