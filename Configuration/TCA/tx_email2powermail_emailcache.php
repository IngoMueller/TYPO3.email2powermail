<?php
defined('TYPO3_MODE') or die();

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
