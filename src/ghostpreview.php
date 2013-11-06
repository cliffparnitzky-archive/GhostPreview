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
require_once('system/initialize.php');

/**
 * Class GhostPreview
 *
 * Frontend ghost preview.
 * @copyright  Cliff Parnitzky 2013
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class GhostPreview extends Backend
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
		// check session and fake backend session
		$strCookie = 'BE_USER_AUTH';
		$hash = sha1(session_id() . (!$GLOBALS['TL_CONFIG']['disableIpCheck'] ? $this->Environment->ip : '') . $strCookie);
		
		$objSession = $this->Database->prepare("SELECT * FROM tl_session WHERE hash = ?")
								   ->limit(1)
									 ->execute($hash); 
		
		// Insert the new session
		if (!$objSession->numRows)
		{
			$this->Database->prepare("INSERT INTO tl_session (pid, tstamp, name, sessionID, ip, hash) VALUES (?, ?, ?, ?, ?, ?)")
								 ->execute(0, time(), $strCookie, session_id(), $this->Environment->ip, $hash); 
			$this->setCookie($strCookie, $hash, (time() + $GLOBALS['TL_CONFIG']['sessionTimeout']), $GLOBALS['TL_CONFIG']['websitePath']);
		}

		$this->Template = new BackendTemplate('be_ghost_preview');

		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->Template->site = $this->Input->get('site', true);
		$this->Template->ghostPreviewToken = $this->Input->get('ghostPreviewToken');
		
		$protocol = 'http://';
		if (strpos($this->Environment->base, 'https://') === 0)
		{
			$protocol = 'https://';
		}
		$this->Template->protocol = ($this->Input->get('addProtocol') && $this->Input->get('addProtocol') == 'true') ? $protocol : "";

		$this->Template->output();
	}
}

/**
 * Instantiate the controller
 */
$objGhostPreview = new GhostPreview();
$objGhostPreview->run();

?>