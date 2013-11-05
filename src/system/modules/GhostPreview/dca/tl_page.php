<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Add button
 */
$GLOBALS['TL_DCA']['tl_page']['list']['operations']['ghostPreview'] = array
(
	'label'               => &$GLOBALS['TL_LANG']['tl_page']['ghostPreview'],
	'icon'                => 'system/modules/GhostPreview/html/ghost_preview.png',
	'button_callback'     => array('tl_page_GhostPreview', 'generateGhostPreviewButton')
);

/**
 * Class tl_page_GhostPreview
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * @copyright  Cliff Parnitzky 2013
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class tl_page_GhostPreview extends Backend
{

	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Return the "ghost preview" button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function generateGhostPreviewButton($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($GLOBALS['TL_CONFIG']['ghostPreviewToken']) == 0)
		{
			return $this->generateImage('system/modules/GhostPreview/html/ghost_preview_token_missing.png', $GLOBALS['TL_LANG']['tl_page']['ghostPreviewTokenMissing'][0], "title=\"" . $GLOBALS['TL_LANG']['tl_page']['ghostPreviewTokenMissing'][1] . "\"");
		}
		return '<a target="_blank" href="ghostpreview.php?ghostPreviewToken='.$GLOBALS['TL_CONFIG']['ghostPreviewToken'].'&amp;site='.$this->generateFrontendUrl($row).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}	
}

?>