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
$GLOBALS['TL_LANG']['tl_news_archive']['newsalert']     = array('Enable news alert', 'Please check here if you want to enable news alert for this archive.');
$GLOBALS['TL_LANG']['tl_news_archive']['subject']       = array('Subject', 'Enter a subject for the e-mails. Use "%s" to include the news headline.');
$GLOBALS['TL_LANG']['tl_news_archive']['sender']        = array('Sender address', 'If you do not enter a sender e-mail address, the administrator e-mail address will be used.');
$GLOBALS['TL_LANG']['tl_news_archive']['senderName']    = array('Sender name', 'Here you can enter the sender\'s name.');
$GLOBALS['TL_LANG']['tl_news_archive']['na_template']   = array('E-mail template', 'Please choose an e-mail template (template group <em>mail_</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['sendText']      = array('Send as text', 'If you choose this option, the e-mail will be sent as text. All HTML tags will be stripped.');
$GLOBALS['TL_LANG']['tl_news_archive']['mailsPerCycle'] = array('Mails per cycle', 'To prevent the script from timing out, the sending process is split into several cycles. Here you can define the number of mails per cycle depending on the maximum execution time defined in your php.ini.');
$GLOBALS['TL_LANG']['tl_news_archive']['timeout']       = array('Timeout in seconds', 'Some mail servers limit the number of e-mails that can be sent per minute. Here you can modify the timeout between each cycle in seconds to get more control over the time frame.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['recipients']	= array('Edit recipients', 'Edit the recipients of archive ID %s');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_archive']['newsalert_legend']	= 'News Alert';

