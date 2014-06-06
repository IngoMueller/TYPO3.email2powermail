<?php

########################################################################
# Extension Manager/Repository config file for ext "email2powermail".
#
# Auto generated 04-01-2011 13:59
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'email2powermail',
	'description' => 'Converts all email addresses in TYPO3 Frontend to a link on a powermail form with the right receiver. Spam prevention because there are no email addresses in FE.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '0.2.0',
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
	'author' => 'Alex Kellner',
	'author_email' => 'alexander.kellner@einpraegsam.net',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'powermail' => '',
			'typo3' => '4.0.0-0.0.0',
			'php' => '5.0.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:17:{s:12:"ext_icon.gif";s:4:"8a2a";s:17:"ext_localconf.php";s:4:"7c3b";s:14:"ext_tables.php";s:4:"5145";s:14:"ext_tables.sql";s:4:"a679";s:38:"icon_tx_email2powermail_emailcache.gif";s:4:"f140";s:37:"icon_tx_email2powermail_receivers.gif";s:4:"f140";s:16:"locallang_db.xml";s:4:"bf19";s:7:"tca.php";s:4:"28ed";s:14:"doc/manual.sxw";s:4:"372f";s:26:"files/static/constants.txt";s:4:"f677";s:22:"files/static/setup.txt";s:4:"b9ae";s:38:"res/class.tx_email2powermail_cache.php";s:4:"2d80";s:36:"res/class.tx_email2powermail_div.php";s:4:"a93f";s:35:"res/class.tx_email2powermail_fe.php";s:4:"4cd5";s:50:"res/class.tx_email2powermail_powermailreceiver.php";s:4:"2e44";s:40:"res/class.tx_email2powermail_replace.php";s:4:"1038";s:38:"res/user_email2powermail_userfuncs.php";s:4:"6a8b";}',
);

?>