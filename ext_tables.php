<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Email 2 Powermail');

$TCA['tx_email2powermail_emailcache'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:email2powermail/locallang_db.xml:tx_email2powermail_emailcache',		
		'label'     => 'email',	
		'hideTable'	=>	1,
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/' . 'tx_email2powermail_emailcache.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'icon_tx_email2powermail_emailcache.gif',
	),
);

$TCA['tx_email2powermail_receivers'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:email2powermail/locallang_db.xml:tx_email2powermail_receivers',		
		'label'     => 'name',	
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'languageField'            => 'sys_language_uid',	
		'transOrigPointerField'    => 'l10n_parent',	
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'default_sortby' => 'ORDER BY crdate',	
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/' . 'tx_email2powermail_receivers.php',
		'iconfile'          => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'icon_tx_email2powermail_receivers.gif',
	),
);
?>
