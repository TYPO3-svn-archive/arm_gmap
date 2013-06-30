<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

//Add controller and actions
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'Arm.'.$_EXTKEY,
	'Map',
	array(
		'Map' => 'index'
	),
	array(
		'Map' => 'index'
	)
);

?>