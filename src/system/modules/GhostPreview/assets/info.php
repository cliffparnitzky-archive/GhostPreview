<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2013
 * @author     Cliff Parnitzky
 * @package    GhostPreview
 * @license    LGPL
 */

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
require_once('../../../initialize.php');

/**
 * Class GhostPreviewInfo
 *
 * Displays an info for ghost preview.
 * @copyright  Cliff Parnitzky 2013
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class GhostPreviewInfo extends Backend
{
	/**
	 * Initialize the controller
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->import('Input');
		// redirect to backend login if token is false
		if (!$this->Input->get('ghostPreviewToken') || $this->Input->get('ghostPreviewToken') != $GLOBALS['TL_CONFIG']['ghostPreviewToken'])
		{
			$this->redirect('contao/index.php');
		}
	}

	/**
	 * Run controller and parse the template
	 */
	public function run()
	{
		// Show unpublished elements
		$this->setCookie('FE_PREVIEW', 1, (time() + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
		// Create template object
		$this->Template = new BackendTemplate('be_ghost_info');
		$this->Template->ghostPreviewToken = $this->Input->get('ghostPreviewToken');
		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->output();
	}
}

/**
 * Instantiate controller
 */
$objGhostPreviewInfo = new GhostPreviewInfo();
$objGhostPreviewInfo->run();

?>