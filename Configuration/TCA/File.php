<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Core\Utility\GeneralUtility::loadTCA('sys_file');

$TCA['sys_file']['types']['1']['showitem'] .= ',preview';

$columns = array (
	'preview' => array (
		'exclude' => 0,
		'label' => 'LLL:EXT:fal_preview/Resources/Private/Language/locallang_db.xml:sys_file.FalPreview.label',
		'config' => array (
			'type' => 'group',
			'internal_type' => 'db',
			'allowed' => 'sys_file',
			'appearance' => array(
				'elementBrowserType' => 'file',
				'elementBrowserAllowed' => 'gif,jpg,jpeg,tif,tiff,bmp,pcx,tga,png'
			),
			'size' => 1,
			'minitems' => 0,
			'maxitems' => 1,
		)
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('sys_file', $columns, 1);

?>
