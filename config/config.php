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
 * Add tables to backend module
 */
$GLOBALS['BE_MOD']['content']['news']['tables'][]	= 'tl_news_recipients';
$GLOBALS['BE_MOD']['content']['news']['stylesheet']	= 'system/modules/newsalert/html/style.css';


/**
 * Add "key" actions
 */
$GLOBALS['BE_MOD']['content']['news']['import']		= array('NewsAlert', 'importRecipients');
$GLOBALS['BE_MOD']['content']['news']['send']		= array('NewsAlert', 'sendButton');


/**
 * Frontend modules
 */
$GLOBALS['FE_MOD']['news']['newsalertsubscribe']	= 'ModuleNewsAlertSubscribe';
$GLOBALS['FE_MOD']['news']['newsalertunsubscribe']	= 'ModuleNewsAlertUnsubscribe';


/**
 * Cron jobs
 */
$GLOBALS['TL_CRON']['hourly'][] = array('NewsAlert', 'cron');


/**
 * Register hooks
 */
$GLOBALS['TL_HOOKS']['activateAccount'][] = array('NewsAlert', 'activateAccount');

