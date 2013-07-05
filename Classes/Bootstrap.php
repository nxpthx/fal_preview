<?php
namespace RsWebsystems\FalPreview;

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
 * Entry point to the FAL Previewer
 *
 * @package fal_preview
 * @author Steffen Ritter <info@rs-websystems.de>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Bootstrap {
	/**
	 * Initializes this package.
	 */
	public static function initialize() {
		self::initializeSignals();
	}

	/**
	 * Initializes signals at the File Abstraction Layer
	 */
	protected static function initializeSignals() {
		//does the dirty work to do a Slot registration for the 'preFileProcess' signal during FAL file processing
		//which does all the magic to replace the original sys_file entity
		//with an alternative sys_file entity for "preview" purposes
		self::getSignalSlotDispatcher()->connect(
				'TYPO3\\CMS\\Core\\Resource\\ResourceStorage',
				\TYPO3\CMS\Core\Resource\Service\FileProcessingService::SIGNAL_PreFileProcess,
				'RsWebsystems\\FalPreview\\Slot\\FileProcessingSlot',
				'preProcess'
		);
	}

	/**
	 * Get the SignalSlot dispatcher
	 *
	 * @return \TYPO3\CMS\Extbase\SignalSlot\Dispatcher
	 */
	public static function getSignalSlotDispatcher() {
		try {
			$signalSlotDispatcher = self::getObjectManager()->get('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
		} catch (\TYPO3\CMS\Core\Cache\Exception\NoSuchCacheException $exception) {
			$signalSlotDispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\SignalSlot\\Dispatcher');
		}

		return $signalSlotDispatcher;
	}

	/**
	 * Get the ObjectManager
	 *
	 * @return \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	public static function getObjectManager() {
		return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
	}
}
?>
