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
$GLOBALS['TL_LANG']['tl_module']['na_subscribe']	= array('Bestell-Bestätigung', 'Bitte geben Sie den Text der Bestätigungsmail ein. Sie können die Platzhalter <em>##archives##</em> (Liste der Archive), <em>##domain##</em> (aktuelle Domain) und <em>##link##</em> (Aktivierungslink) verwenden.');
$GLOBALS['TL_LANG']['tl_module']['na_unsubscribe']	= array('Abbestell-Bestätigung', 'Bitte geben Sie den Text der Bestätigungsmail ein. Sie können die Platzhalter <em>##archives##</em> (Name des Newsletters) und <em>##domain##</em> (aktuelle Domain) verwenden.');
$GLOBALS['TL_LANG']['tl_module']['newsalerts']		= array('Abonnierbare News-Benachrichtigungen', 'Diese Nachrichten-Archive im Frontend-Formular anzeigen.');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['na_text_subscribe']   = array('Ihr Abonnement auf %s', "Sie haben auf ##domain## eine Benachrichtigung für folgende News bestellt:\n##archives##\n\nBitte klicken Sie ##link## um Ihre Bestellung zu bestätigen. Bitte ignorieren Sie diese Email falls Sie die Bestellung nicht selbst getätigt haben.\n");
$GLOBALS['TL_LANG']['tl_module']['na_text_unsubscribe'] = array('Ihr Abonnement auf %s', "Sie haben die Benachrichtigung für folgende News abbestellt:\n##archives##\n");

