<?php
namespace Arm\ArmGmap\Controller;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Anisur R. Mullick <anisur@armtechnologies.com>, ARM Technologies
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
 * For TYPO3 6.x
 * Display Google Map with marker. The width, height, zoom can be configured using flexform.
 *
 * @package TYPO3
 * @subpackage arm_gmap
 * @author Anisur R. Mullick <anisur@armtechnologies.com>
 * @version 1.1.0
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class MapController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {	
	
	/**
	 * Used for multiple google map in same page
	 * 
	 * @var \string
	 */
	protected $containerId;
	
	/**
	 * 
	 * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
	 */
	protected $cObject;
	
	/**
	 * The default index action
	 * @return string
	 */
	public function indexAction() {
		
		//Get the content object
		$this->cObject = $this->configurationManager->getContentObject();
		//Populate the map specific jQuery libraries
		$this->addHeaderJs();
		
		//Check whether customer container id provided in flexform
		$this->containerId = (empty($this->settings['container']))?'gmap':$this->settings['container'];
		//Create block with this ed
		$this->view->assign('containerId', $this->containerId);
		//If no lat/long provided, dynamically fetch with geocode (slow)
		if (empty($this->settings['lat']) || empty($this->settings['long'])) {
			$this->view->assign('enableGeocode',1);
		}
		else {
			//assign the latitude and longitude information from flexform
			$this->view->assign('latitude',$this->settings['lat']);
			$this->view->assign('longitude',$this->settings['long']);
		}
		//Set Map zoom
		$zoom = (empty($this->settings['zoom']))?16:$this->settings['zoom'];
		$this->view->assign('zoom', $zoom);
		
		//Population of info box
		$addressArr = array();
		
		if (trim($this->settings['address']) != '') {
			$addressArr[] = trim($this->settings['address']);
		}
		if (trim($this->settings['city']) != '') {
			$addressArr[] = trim($this->settings['city']);
		}
		if (trim($this->settings['zip']) != '') {
			$addressArr[] = trim($this->settings['zip']);
		}
		if (trim($this->settings['country']) != '') {
			$addressArr[] = trim($this->settings['country']);
		}
		$fullAddress = implode(',+', $addressArr);
		
		//Assign the values to Fluid template variables
		$this->view->assign('uid', $this->cObject->data['uid']);
		$this->view->assign('address', $fullAddress);
		$this->view->assign('title', trim($this->settings['title']));
		$this->view->assign('street', trim($this->settings['address']));
		$this->view->assign('zip',  trim($this->settings['zip']));
		$this->view->assign('city', trim($this->settings['city']));
	}
	
	/**
	 * Adds the GMap API js
	 */
	protected function addHeaderJs() {
		//name of the JS blocks
		$one = $this->request->getControllerExtensionName().'_apijs';
		$two = $this->request->getControllerExtensionName().'_gmap3js';
		
		//Add the JS blocks to page header
		$GLOBALS['TSFE']->additionalHeaderData[$one] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
		$GLOBALS['TSFE']->additionalHeaderData[$two] = '<script type="text/javascript" src="'.\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('arm_gmap').'Resources/Public/Js/jquery.gmap3.min.js"></script>';
	}
}