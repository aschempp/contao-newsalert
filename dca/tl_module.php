<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight webCMS
 * Copyright (C) 2005-2009 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2009
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * Config
 */
$GLOBALS['TL_DCA']['tl_module']['config']['onload_callback'][] = array('NewsAlert', 'injectFields');


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsalertsubscribe'] = '{title_legend},name,headline,type;{config_legend},news_archives;{redirect_legend},jumpTo;{email_legend:hide},na_subscribe;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['newsalertunsubscribe'] = '{title_legend},name,headline,type;{config_legend},news_archives;{redirect_legend},jumpTo;{email_legend:hide},na_unsubscribe;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['na_subscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['na_subscribe'],
	'default'                 => &$GLOBALS['TL_LANG']['tl_module']['na_text_subscribe'][1],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('tl_module_newsalert', 'getDefaultValue')
	)
);

$GLOBALS['TL_DCA']['tl_module']['fields']['na_unsubscribe'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['na_unsubscribe'],
	'default'                 => &$GLOBALS['TL_LANG']['tl_module']['na_text_unsubscribe'][1],
	'exclude'                 => true,
	'inputType'               => 'textarea',
	'eval'                    => array('style'=>'height:120px;', 'decodeEntities'=>true),
	'save_callback' => array
	(
		array('tl_module_newsalert', 'getDefaultValue')
	)
);


$GLOBALS['TL_DCA']['tl_module']['fields']['newsalerts'] = array
(
	'label'						=> &$GLOBALS['TL_LANG']['tl_module']['newsalerts'],
	'exclude'					=> true,
	'inputType'					=> 'checkbox',
	'options_callback'			=> array('NewsAlert', 'getNewsArchives'),
	'eval'						=> array('multiple'=>true)
);


class tl_module_newsalert extends Backend
{

	/**
	 * Load the default value if the text is empty
	 * @param string
	 * @param object
	 * @return string
	 */
	public function getDefaultValue($varValue, DataContainer $dc)
	{
		if (!strlen(trim($varValue)))
		{
			$varValue = $GLOBALS['TL_DCA'][$dc->table]['fields'][$dc->field]['default'];
		}

		return $varValue;
	}
}

