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
 * @copyright  Andreas Schempp 2008-2010
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id$
 */


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_news_recipients']['email']  	= array('E-mail address', 'Please enter the recipient\'s e-mail address.');
$GLOBALS['TL_LANG']['tl_news_recipients']['active'] 	= array('Activated', 'E-mail addresses are usually activated by clicking a link in the confirmation e-mail (double-opt-in).');
$GLOBALS['TL_LANG']['tl_news_recipients']['source']		= array('File source', 'Please choose the CSV file you want to import from the files directory.');
$GLOBALS['TL_LANG']['tl_news_recipients']['ip']			= array('IP address', 'The IP address of the subscriber.');
$GLOBALS['TL_LANG']['tl_news_recipients']['addedOn']	= array('Subscription date', 'The date of subscription.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_news_recipients']['confirm']	= '%s recipients have been imported.';
$GLOBALS['TL_LANG']['tl_news_recipients']['subscribed'] = 'subscribed on %s';
$GLOBALS['TL_LANG']['tl_news_recipients']['manually']   = 'added manually';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_recipients']['new']    = array('Add recipient', 'Add a new recipient');
$GLOBALS['TL_LANG']['tl_news_recipients']['edit']   = array('Edit recipient', 'Edit recipient ID %s');
$GLOBALS['TL_LANG']['tl_news_recipients']['copy']   = array('Copy recipient', 'Copy recipient ID %s');
$GLOBALS['TL_LANG']['tl_news_recipients']['delete'] = array('Delete recipient', 'Delete recipient ID %s');
$GLOBALS['TL_LANG']['tl_news_recipients']['show']   = array('Recipient details', 'Show details of recipient ID %s');
$GLOBALS['TL_LANG']['tl_news_recipients']['import'] = array('CSV import', 'Import recipients from a CSV file');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_recipients']['email_legend'] = 'E-mail address';

