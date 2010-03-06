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
$GLOBALS['TL_LANG']['tl_news_archive']['newsalert']     = array('News-Benachrichtigung aktivieren', 'Bitte klicken Sie hier wenn die E-Mail Benachrichtigung für dieses Archiv aktiviert werden soll.');
$GLOBALS['TL_LANG']['tl_news_archive']['subject']       = array('Betreff', 'Geben Sie einen Betreff für die E-Mails ein. Benutzen Sie "%s" um die Überschrift der Nachricht einzufügen.');
$GLOBALS['TL_LANG']['tl_news_archive']['sender']        = array('Absenderadresse', 'Wenn Sie keine Absenderadresse eingeben, wird die Emailadresse des Administrators verwendet.');
$GLOBALS['TL_LANG']['tl_news_archive']['senderName']    = array('Absendername', 'Hier können Sie den Namen des Absenders eingeben.');
$GLOBALS['TL_LANG']['tl_news_archive']['na_template']   = array('E-Mail Template', 'Bitte wählen Sie ein E-Mail Template (Templategruppe <em>mail_</em>).');
$GLOBALS['TL_LANG']['tl_news_archive']['sendText']      = array('Als Text senden', 'Wenn Sie diese Option wählen, wird die News-Benachrichtigung als Text versendet. Alle HTML Tags werden dabei entfernt.');
$GLOBALS['TL_LANG']['tl_news_archive']['mailsPerCycle'] = array('Mails pro Zyklus', 'Um zu verhindern dass das Skript vorzeitig abbricht, wird der Prozess in mehreren Teilschritten ausgeführt. Hier können Sie die Anzahl der Mails pro Teilschritt im Verhältnis zur maximalen Skriptlaufzeit in Ihrer php.ini festlegen.');
$GLOBALS['TL_LANG']['tl_news_archive']['timeout']       = array('Wartezeit in Sekunden', 'Einige Mailserver beschränken die Anzahl der E-Mails, die pro Minute versendet werden können. Durch eine Anpassung der Wartezeit zwischen den einzelnen Versandzyklen in Sekunden, lässt sich der zeitlichen Aspekt des Versands beeinflussen.');


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_news_archive']['recipients']	= array('Empfänger bearbeiten', 'Empfänger des Archiv ID %s bearbeiten');


/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_news_archive']['newsalert_legend']	= 'News-Benachrichtigung';

