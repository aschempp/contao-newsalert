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
$GLOBALS['TL_LANG']['tl_news']['newsalert'] = array('Send news alert', 'Please check here if you want to send an e-mail for this news.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_newsalert']['sent']        = 'Sent';
$GLOBALS['TL_LANG']['tl_newsalert']['sentOn']      = 'Sent on %s';
$GLOBALS['TL_LANG']['tl_newsalert']['notSent']     = 'Not sent yet';
$GLOBALS['TL_LANG']['tl_newsalert']['headline']    = 'Send news alert';
$GLOBALS['TL_LANG']['tl_newsalert']['confirm']     = 'The news alert has been sent to %s recipients.';
$GLOBALS['TL_LANG']['tl_newsalert']['error']       = 'There are no active subscribers to this news archive.';
$GLOBALS['TL_LANG']['tl_newsalert']['from']        = 'From';
$GLOBALS['TL_LANG']['tl_newsalert']['attachments'] = 'Attachments';
$GLOBALS['TL_LANG']['tl_newsalert']['subject']    = 'Subject';
$GLOBALS['TL_LANG']['tl_newsalert']['send_again']  = 'Do you really want to send this news alert again?';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_newsalert']['send']       = array('Send News-Alert', 'Send news alert ID %s');

