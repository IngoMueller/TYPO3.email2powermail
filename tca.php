<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA['tx_email2powermail_emailcache'] = array (
	'ctrl' => $TCA['tx_email2powermail_emailcache']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'email'
	),
	'feInterface' => $TCA['tx_email2powermail_emailcache']['feInterface'],
	'columns' => array (
		'email' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:email2powermail/locallang_db.xml:tx_email2powermail_emailcache.email',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'email;;;;1-1-1')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);

$TCA['tx_email2powermail_receivers'] = array (
	'ctrl' => $TCA['tx_email2powermail_receivers']['ctrl'],
	'interface' => array (
		'showRecordFieldList' => 'email,name'
	),
	'feInterface' => $TCA['tx_email2powermail_emailcache']['feInterface'],
	'columns' => array (
		'sys_language_uid' => array (		
			'exclude' => 1,
			'label'  => 'LLL:EXT:lang/locallang_general.xml:LGL.language',
			'config' => array (
				'type'                => 'select',
				'foreign_table'       => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xml:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xml:LGL.default_value', 0)
				)
			)
		),
		'l10n_parent' => array (		
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude'     => 1,
			'label'       => 'LLL:EXT:lang/locallang_general.xml:LGL.l18n_parent',
			'config'      => array (
				'type'  => 'select',
				'items' => array (
					array('', 0),
				),
				'foreign_table'       => 'tx_email2powermail_receivers',
				'foreign_table_where' => 'AND tx_email2powermail_receivers.pid=###CURRENT_PID### AND tx_email2powermail_receivers.sys_language_uid IN (-1,0)',
			)
		),
		'l10n_diffsource' => array (		
			'config' => array (
				'type' => 'passthrough'
			)
		),
		'email' => array (		
			'exclude' => 1,
			'l10n_mode' => 'exclude',
			'label' => 'LLL:EXT:email2powermail/locallang_db.xml:tx_email2powermail_receivers.email',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required,unique',
			)
		),
		'name' => array (		
			'exclude' => 1,		
			'label' => 'LLL:EXT:email2powermail/locallang_db.xml:tx_email2powermail_receivers.name',		
			'config' => array (
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required',
			)
		),
	),
	'types' => array (
		'0' => array('showitem' => 'email;;;;1-1-1, name')
	),
	'palettes' => array (
		'1' => array('showitem' => '')
	)
);
?>