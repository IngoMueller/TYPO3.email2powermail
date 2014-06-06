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
 * Plugin 'tx_email2powermail_cache' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage tx_email2powermail_cache
 */
class tx_email2powermail_cache extends tslib_pibase {
	
	public $prefixId      = 'tx_email2powermail';
	public $scriptRelPath = 'res/class.tx_email2powermail_cache.php'; // Path to this script relative to the extension dir.
	public $extKey        = 'email2powermail'; // The extension key.

	private $dbInsert = 1; // disable db insert only for testing
	
	
	/**
	 * Main function to get uid of an email address
	 *
	 * @param	string		$email: current email address
	 * @param	array		$pObj: parent Object
	 * @return	string		$uid: uid of email address
	 */
	public function main(&$email, &$pObj) {
		// config
		$this->div = $pObj->div; // get div methods
		$this->conf = $pObj->conf; // get TS
		
		// Let's go
		// 1. remove old entries from database
		$this->removeOldDbEntries();
		
		// 2. see if there is already an entry in the database
		$uid = $this->checkDbForEmail($email);
		
		// 3. if there is no entry, generate a new entry in database
		if (!$uid) { // if there is no uid available
			$uid = $this->addDbEntry($email); // generate new entry
		}
		
		// 4. return email uid
		return $uid;
		
	}
	
	
	/**
	 * Add a new cache entry
	 *
	 * @param	string		$email: current email address
	 * @return	string
	 */
	public function addDbEntry($email) {
		if ($this->dbInsert) { // if db inserts are allowed
			$dbvalues = array(
				'email' => $this->div->removeMailto($email),
				'crdate' => time(),
				'tstamp' => time(),
			);
			$GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_email2powermail_emailcache', $dbvalues); // DB entry
		}
	}
	
	
	/**
	 * Give uid of existing email in cache table
	 *
	 * @param	string		$email: current email address
	 * @return	string		$uid: UID of email
	 */
	public function checkDbForEmail($email) {
		$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery (
			'tx_email2powermail_emailcache.uid',
			'tx_email2powermail_emailcache',
			$where = 'tx_email2powermail_emailcache.email = ' . $GLOBALS['TYPO3_DB']->fullQuoteStr($this->div->removeMailto($email), 'tx_email2powermail_emailcache'),
			$groupBy = '',
			$orderBy = '',
			$limit = 1
		);
		if ($res) $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
		
		if ($row['uid'] > 0) { // if result
			return $row['uid']; // return email
		} else { // or
			return false; // return false
		}
	}
	
	
	/**
	 * Remove old db entries
	 *
	 * @return	void
	 */
	public function removeOldDbEntries() {
		// remove all entries which are older than allowed (see constants)
		$res = $GLOBALS['TYPO3_DB']->exec_DELETEquery(
			'tx_email2powermail_emailcache',
			'crdate < ' . (time() - intval($this->conf['cachetime']))
		);
	}
	
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_cache.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_cache.php']);
}

?>