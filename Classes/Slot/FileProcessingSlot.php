<?php
namespace RsWebsystems\FalPreview\Slot;

/***************************************************************
*  Copyright notice
*
*  (c) 2013 Steffen Ritter (info@rs-websystems.de)
*
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * Class encapsulating the slot registered functions for file processing signals
 *
 * @package fal_preview
 * @author Steffen Ritter <info@rs-websystems.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class FileProcessingSlot implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Pre-processor slot to intercept and manipulate source file used for
	 * 1. Image Crop/Scale/Masking Task (for FE mode)
	 * 2. Image Preview Task (for BE mode)
	 *
	 * @param  \TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService
	 * @param  \TYPO3\CMS\Core\Resource\Driver\AbstractDriver		 $driver
	 * @param  \TYPO3\CMS\Core\Resource\ProcessedFile				 $processedFile
	 * @param  \TYPO3\CMS\Core\Resource\FileInterface				 $file
	 * @param  string												 $context
	 * @param  array												  $configuration
	 * @return void
	 */
	public function preProcess(
			\TYPO3\CMS\Core\Resource\Service\FileProcessingService $fileProcessingService,
			\TYPO3\CMS\Core\Resource\Driver\AbstractDriver $driver,
			\TYPO3\CMS\Core\Resource\ProcessedFile $processedFile,
			\TYPO3\CMS\Core\Resource\FileInterface $file,
			$context, array $configuration
	) {
		//Get configuration array
		$extConf = &$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][FALPREVIEW_EXTKEY];

		//Check if the current context is supported for further processing
		if (in_array($context, $extConf['supportedRenderingContext'])) {

			//If context is BE then check if currently processed file is an image,
			//if true, no need to replace with an alternative 'sys_file' entity, hence no further proc required
			//if false, go ahead and replace
			if($context == \TYPO3\CMS\Core\Resource\ProcessedFile::CONTEXT_IMAGEPREVIEW &&
					$processedFile->getType() == \TYPO3\CMS\Core\Resource\ProcessedFile::FILETYPE_IMAGE) {
				return;
			}

			//get referred 'sys_file' entity UID
			$referredEntityId = $file->getProperty('preview');
			if ($referredEntityId) {
				//get referred 'sys_file' entity object
				$referredEntityObject = \TYPO3\CMS\Core\Resource\ResourceFactory::getInstance()->getFileObject($referredEntityId);
				if ($referredEntityObject) {

					//if the referred 'sys_file' entity object actually encapsulates a file that exists
					if ($referredEntityObject->getStorage()->hasFile($referredEntityObject->getProperty('identifier'))) {

						//Get the current task processing the file
						//@var \TYPO3\CMS\Core\Resource\Processing\AbstractTask
						$task = $processedFile->getTask();

						//replace the task's source file with the alternative 'sys_file' entity object
						$task->setSourceFile($referredEntityObject);
					}
				}
			}
		}
	}
}

?>
