<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

//add non-image file types to renderable items list
$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$_EXTKEY]);
$TYPO3_CONF_VARS['GFX']['imagefile_ext'] .= ',' . $extConf['file_ext_preview_supported'];

//Configurable parameter to switch on/off the "preview" based on an alternative sys_file
//on a per rendering context basis (FE: Crop,Scale,Mask / BE: Preview)
$TYPO3_CONF_VARS['EXTCONF'][$_EXTKEY] = array (
	'supportedRenderingContext' => array (
		\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGECROPSCALEMASK,
		\TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGEPREVIEW,
	)
);

//holds the current extension key
define('FALPREVIEW_EXTKEY', $_EXTKEY);

//Bootstrap
\RsWebsystems\FalPreview\Bootstrap::initialize();

?>
