<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}


###############################################
## Hook for HTML manipulation of output #######
###############################################

# Hooks for TYPO3 FE manipulation
	// hook is called after Caching / pages with COA_/USER_INT objects. 
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-output'][] = 'EXT:email2powermail/res/class.tx_email2powermail_fe.php:&tx_email2powermail_fe->noCache';

	// hook is called before Caching / pages on their way in the cache.
$TYPO3_CONF_VARS['SC_OPTIONS']['tslib/class.tslib_fe.php']['contentPostProc-all'][] = 'EXT:email2powermail/res/class.tx_email2powermail_fe.php:&tx_email2powermail_fe->cache';

# Hooks for powermail manipulation
	// Hook PM_FormWrapMarkerHook: If piVars pm_receiver > 0 than write email of receiver to session
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_FormWrapMarkerHook'][] = 'EXT:email2powermail/res/class.tx_email2powermail_powermailreceiver.php:tx_email2powermail_powermailreceiver';

	// Hook PM_SubmitEmailHook: Change E-Mail Receiver if there is any email in the session
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['powermail']['PM_SubmitEmailHook'][] = 'EXT:email2powermail/res/class.tx_email2powermail_powermailreceiver.php:tx_email2powermail_powermailreceiver';

?>