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
 * Operations
 */
array_insert($GLOBALS['TL_DCA']['tl_news_archive']['list']['operations'], 4, array
(
	'recipients' => array
	(
		'label'               => &$GLOBALS['TL_LANG']['tl_news_archive']['recipients'],
		'href'                => 'table=tl_news_recipients',
		'icon'                => 'mgroup.gif'
	)
));


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['default'] .= ';{newsalert_legend},newsalert';
$GLOBALS['TL_DCA']['tl_news_archive']['palettes']['__selector__'][] = 'newsalert';
$GLOBALS['TL_DCA']['tl_news_archive']['subpalettes']['newsalert'] = 'sender,senderName,subject,na_template,sendText';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['newsalert'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['newsalert'],
	'inputType'				  => 'checkbox',
	'eval'					  => array('submitOnChange'=>true),
);
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['subject'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['subject'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>128),
);
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['sender'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['sender'],
	'exclude'                 => true,
	'search'                  => true,
	'filter'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('rgxp'=>'email', 'maxlength'=>128),
);
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['senderName'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['senderName'],
	'exclude'                 => true,
	'search'                  => true,
	'inputType'               => 'text',
	'eval'                    => array('decodeEntities'=>true, 'maxlength'=>128),
);
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['na_template'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['na_template'],
	'default'                 => 'mail_newsalert_en',
	'exclude'                 => true,
	'inputType'               => 'select',
	'options'                 => $this->getTemplateGroup('mail_')
);
$GLOBALS['TL_DCA']['tl_news_archive']['fields']['sendText'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_news_archive']['sendText'],
	'exclude'                 => true,
	'filter'                  => true,
	'inputType'               => 'checkbox'
);

