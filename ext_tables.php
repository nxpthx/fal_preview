<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

//extend and include the 'sys_file' TCA
require \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/File.php';

?>
