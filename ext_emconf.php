<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'email2powermail',
	'description' => 'Converts all email addresses in TYPO3 Frontend to a link on a powermail form with the right receiver. Spam prevention because there are no email addresses in FE.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'alpha',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Alex Kellner, Kay Strobach',
	'author_email' => 'kay.strobach@typo3.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'powermail' => '',
			'typo3' => '6.2.0-7.9.99',
			'cms' => '',
			'extbase' => ''
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:0:{}',
);


