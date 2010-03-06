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
 * Configuration
 */
$GLOBALS['TL_DCA']['tl_news']['config']['onload_callback'][] = array('tl_news_alert', 'enableAlert');
$GLOBALS['TL_DCA']['tl_news']['list']['sorting']['headerFields'][] = 'newsalert';


/**
 * Operations
 */
$GLOBALS['TL_DCA']['tl_news']['list']['operations']['send'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_news']['send'],
	'href'				=> 'key=send',
	'icon'				=> 'system/modules/newsalert/html/send.gif',
	'button_callback'   => array('tl_news_alert', 'showSend'),
);


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_news']['fields']['newsalert'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['tl_news']['newsalert'],
	'inputType'			=> 'checkbox',
);
 

class tl_news_alert extends Backend
{

	/**
	 * Enable the send button if news alert is active
	 */
	public function showSend($row, $href, $label, $title, $icon, $attributes)
	{
		if ($row['newsalert'] && $this->Database->prepare("SELECT newsalert FROM tl_news_archive WHERE tl_news_archive.id=?")->execute($row['pid'])->newsalert)
		{
			if ($row['na_sent'])
			{
				return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.' onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['tl_newsalert']['send_again'] . '\')) return false;">'.$this->generateImage(str_replace('send', 'sent', $icon), $label).'</a> ';
			}

			return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		}

		return $this->generateImage(str_replace('.gif', '_.gif', $icon), $label);
	}
	
	
	/**
	 * Display news alert checkbox if the archive allows it
	 */
	public function enableAlert(DataContainer $dc)
	{
		if (!$this->Input->get('pid') && $this->Input->get('act') == 'edit' && $this->Input->get('id'))
		{
			$this->Input->setGet('pid', $this->Database->prepare("SELECT pid FROM tl_news WHERE id=?")->execute($this->Input->get('id'))->pid);
		}
		
		if ($this->Input->get('act') != '' && $this->Input->get('act') != 'delete' && $this->Input->get('act') != 'paste' && $this->Database->prepare("SELECT newsalert FROM tl_news_archive WHERE id=?")->execute($this->Input->get('pid'))->newsalert)
		{
				$GLOBALS['TL_DCA']['tl_news']['palettes']['default'] = str_replace('published,', 'published,newsalert,', $GLOBALS['TL_DCA']['tl_news']['palettes']['default']);
		}
	}
}

