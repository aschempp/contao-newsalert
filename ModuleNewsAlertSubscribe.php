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


class ModuleNewsAlertSubscribe extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newsalert_subscribe';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			
			$objTemplate->wildcard = '### NEWS ALERT SUBSCRIBE ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'typolight/main.php?do=modules&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}
		
		$this->news_archives = deserialize($this->news_archives);
		
		if (!is_array($this->news_archives) || !count($this->news_archives))
			return '';

		return parent::generate();
	}


	/**
	 * Generate module
	 */
	protected function compile()
	{
		// Activate e-mail address
		if ($this->Input->get('token'))
		{
			$this->activateRecipient();
			return;
		}

		// Subscribe
		if ($this->Input->post('FORM_SUBMIT') == 'tl_subscribe')
		{
			$this->addRecipient();
		}

		// Messages
		if (strlen($_SESSION['SUBSCRIBE_ERROR']))
		{
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['SUBSCRIBE_ERROR'];
			$_SESSION['SUBSCRIBE_ERROR'] = '';
		}

		if (strlen($_SESSION['SUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['SUBSCRIBE_CONFIRM'];
			$_SESSION['SUBSCRIBE_CONFIRM'] = '';
		}
		
		// Generate archive checkboxes
		$objArchives = $this->Database->execute("SELECT * FROM tl_news_archive WHERE id IN (" . implode(',', $this->news_archives) . ")");
		$arrArchives = array();
		while( $objArchives->next() )
		{
			$arrArchives[$objArchives->id] = $objArchives->title;
		}
		$this->Template->archives = $arrArchives;

		// Default template variables
		$this->Template->email = '';
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['na_subscribe']);
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->formId = 'tl_subscribe';
	}


	/**
	 * Activate a recipient
	 */
	private function activateRecipient()
	{
		$this->Template = new FrontendTemplate('mod_message');

		$objRecipient = $this->Database->prepare("SELECT * FROM tl_news_recipients WHERE token=?")
									   ->limit(1)
									   ->execute($this->Input->get('token'));

		if ($objRecipient->numRows < 1)
		{
			$this->Template->type = 'error';
			$this->Template->message = $GLOBALS['TL_LANG']['ERR']['invalidToken'];

			return;
		}

		$this->Database->prepare("UPDATE tl_news_recipients SET active=?, token=? WHERE token=?")
					   ->execute(1, '', $this->Input->get('token'));

		$this->Template->type = 'confirm';
		$this->Template->message = $GLOBALS['TL_LANG']['MSC']['na_activate'];
	}


	/**
	 * Add a new recipient
	 */
	private function addRecipient()
	{
		$arrArchives = $this->Input->post('archive');
		
		if (!is_array($arrArchives) || count($arrArchives) < 1)
		{
			$this->log('No news-alert channel selected', 'ModuleSubscribe addRecipient()', 5);
			$this->reload();
		}

		if (!preg_match('/^\w+([_\.-]*\w+)*@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $this->Input->post('email')))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$objEmail = $this->Database->prepare("SELECT active FROM tl_news_recipients WHERE email=? AND pid IN (" . implode(',', $arrArchives) . ")")
								   ->limit(1)
								   ->execute($this->Input->post('email'));

		if ($objEmail->numRows)
		{
			$arrActive = $objEmail->fetchEach('active');
			if (count($arrActive) == count($arrArchives) && array_search('', $arrActive) !== false)
			{
				$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['subscribed'];
				$this->reload();
			}

			$this->Database->prepare("DELETE FROM tl_news_recipients WHERE email=? AND pid IN (" . implode(',', $arrArchives) . ")")
						   ->execute($this->Input->post('email'));
		}


		// All archives use the same token, so we can enable all at once
		$strToken = md5(uniqid('', true));

		foreach( $arrArchives as $archive )
		{
			$arrSet = array
			(
				'pid'		=> $archive,
				'tstamp'	=> time(),
				'email'		=> $this->Input->post('email'),
				'active'	=> '',
				'token'		=> $strToken,
				'addedOn'	=> time(),
				'ip'		=> $this->Environment->ip,
			);
	
			$this->Database->prepare("INSERT INTO tl_news_recipients %s")
						   ->set($arrSet)
						   ->execute();
		}

		// Activation e-mail
		$objEmail = new Email();

		$arrTitles = $this->Database->prepare("SELECT title FROM tl_news_archive WHERE id IN (" . implode(',', $arrArchives) . ")")
									->execute()
									->fetchEach('title');

		$strText = str_replace('##archives##', '- ' . implode("\n- ", $arrTitles), $this->na_subscribe);
		$strText = str_replace('##domain##', $this->Environment->host, $strText);
		$strText = str_replace('##link##', $this->Environment->base . $this->Environment->request . '?token=' . $strToken, $strText);

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['na_subject'], $this->Environment->host);
		$objEmail->text = $strText;

		$objEmail->sendTo($this->Input->post('email'));

		// Redirect to jumpTo page
		global $objPage;

		if (strlen($this->jumpTo) && $this->jumpTo != $objPage->id)
		{
			$objNextPage = $this->Database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
										  ->limit(1)
										  ->execute($this->jumpTo);

			if ($objNextPage->numRows)
			{
				$this->redirect($this->generateFrontendUrl($objNextPage->fetchAssoc()));
			}
		}

		$_SESSION['SUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['na_confirm'];
		$this->reload();
	}
}

