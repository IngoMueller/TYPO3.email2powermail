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

require_once(PATH_tslib . 'class.tslib_pibase.php');


/**
 * Plugin 'tx_email2powermail_powermailreceiver' for the 'email2powermail' extension.
 *
 * @author	Alex Kellner <alexander.kellner@einpraegsam.net>
 * @package	TYPO3
 * @subpackage tx_email2powermail_powermailreceiver
 */
class tx_email2powermail_powermailreceiver extends tslib_pibase {

	public $prefixId      = 'tx_email2powermail';
	public $scriptRelPath = 'res/class.tx_email2powermail_fe.php'; // Path to this script relative to the extension dir.
	public $extKey        = 'email2powermail'; // The extension key.
	
	
	/**
	 * Function PM_FormWrapMarkerHook() called from powermail hook to set email receiver in session for powermail (if form is rendered in powermail)
	 *
	 * @param	array		$OuterMarkerArray: Marker Array of powermail formwrap template
	 * @param	array		$subpartArray: Array with subparts
	 * @param	array		$conf: Powermail TS configuration
	 * @param	array		$obj: parent object
	 * @return	void
	 */
    public function PM_FormWrapMarkerHook($OuterMarkerArray, $subpartArray, $conf, $obj) {
		$this->piVars = t3lib_div::_GP($this->prefixId); // enable piVars
		
		if ($this->piVars['uid'] > 0) { // if pm_receiver is set in piVars
			// Get email from caching table
			$res = $GLOBALS['TYPO3_DB']->exec_SELECTquery ( // DB query
				'email',
				'tx_email2powermail_emailcache',
				$where_clause = 'tx_email2powermail_emailcache.uid = ' . intval($this->piVars['uid']) . tslib_cObj::enableFields('tx_email2powermail_emailcache'),
				$groupBy = '',
				$orderBy = '',
				$limit = 1
			);
			if ($res) $row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res); // Result in array
			
			if (t3lib_div::validEmail($row['email'])) { // if this is a valid email address
				// Set Session
				$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS['TSFE']->id, array('powermailreceiver' => $row['email']));
				$GLOBALS['TSFE']->storeSessionData(); // Save session
			}
			
		} else { // pm_receiver is not set in piVars (clear session if user comes again to the same form)
			
			$rec_array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS['TSFE']->id); // get session values
			$pm_array1 = $GLOBALS['TSFE']->fe_user->getKey('ses', 'powermail_' . ($obj->cObj->data['_LOCALIZED_UID'] > 0 ? $obj->cObj->data['_LOCALIZED_UID'] : $obj->cObj->data['uid']));
			$pm_array2 = $GLOBALS['TSFE']->fe_user->getKey('ses', 'powermail_' . ($obj->cObj->data['_LOCALIZED_UID'] > 0 ? $obj->cObj->data['_LOCALIZED_UID'] : $obj->cObj->data['pid']));
			
			if ($rec_array['powermailreceiver'] && empty($pm_array1) && empty($pm_array2)) { // there is an old value in the session and no value in the powermail session (should not be cleared if user goes back from confirm to form)
				// Clear Session
				$GLOBALS['TSFE']->fe_user->setKey('ses', $this->extKey . '_' . $GLOBALS['TSFE']->id, array()); // empty array
				$GLOBALS['TSFE']->storeSessionData(); // Save session to overwrite session
			}
				
		}
    }
	
	
	/**
	 * Function PM_SubmitEmailHook() to change powermail email receiver if value in session (if submit.php is rendered in powermail)
	 *
	 * @param	string		$subpart: Gives hint if this is an email to the receiver or to the sender
	 * @param	array		$maildata: Array with maildata (email, subject, etc...)
	 * @param	array		$sessiondata: Values from powermail session
	 * @param	array		$markerArray: markerArray in email
	 * @param	array		$obj: parent object
	 * @return	void
	 */
    function PM_SubmitEmailHook($subpart, &$maildata, $sessiondata, $markerArray, &$obj) {
		
		if ($subpart == 'recipient_mail') { // should work only if mail to receiver
			$rec_array = $GLOBALS['TSFE']->fe_user->getKey('ses', $this->extKey . '_' . $GLOBALS['TSFE']->id); // get email from wt_directory session
			
			if (t3lib_div::validEmail($rec_array['powermailreceiver'])) { // if this is a valid email address
				$maildata['receiver'] = $rec_array['powermailreceiver']; // change email receiver
				$obj->MainReceiver = $rec_array['powermailreceiver']; // change email receiver (save db values)
			}
		}
    }
	
	
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_powermailreceiver.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/email2powermail/res/class.tx_email2powermail_powermailreceiver.php']);
}

?>