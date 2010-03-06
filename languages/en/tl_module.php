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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['na_subscribe']	= array('Subscription e-mail', 'Please enter the text of the subscription confirmation e-mail. You can use the wildcards <em>##archives##</em> (list of archives), <em>##domain##</em> (current domain name) and <em>##link##</em> (activation link).');
$GLOBALS['TL_LANG']['tl_module']['na_unsubscribe']	= array('Unsubscription e-mail', 'Please enter the text of the unsubscription confirmation e-mail. You can use the wildcards <em>##archives##</em> (channel name) and <em>##domain##</em> (current domain name).');
$GLOBALS['TL_LANG']['tl_module']['newsalerts']		= array('Subscribable news-alerts', 'Show these news archives in the front end form.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['na_text_subscribe']   = array('Your subscription on %s', "You have subscribed to to the following news on ##domain##:\n##archives##\n\nPlease click ##link## to complete your subscription. If you did not subscribe yourself, please ignore this e-mail.\n");
$GLOBALS['TL_LANG']['tl_module']['na_text_unsubscribe'] = array('Your subscription on %s', "You have successfully unsubscribed from the following news:##archives##\n");

