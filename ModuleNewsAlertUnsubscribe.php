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


class ModuleNewsAlertUnsubscribe extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newslaert_unsubscribe';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			
			$objTemplate->wildcard = '### NEWS ALERT UNSUBSCRIBE ###';
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
		// Unsubscribe
		if ($this->Input->post('FORM_SUBMIT') == 'tl_unsubscribe')
		{
			$this->removeRecipient();
		}

		// Messages
		if (strlen($_SESSION['UNSUBSCRIBE_ERROR']))
		{
			$this->Template->mclass = 'error';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_ERROR'];
			$_SESSION['UNSUBSCRIBE_ERROR'] = '';
		}

		if (strlen($_SESSION['UNSUBSCRIBE_CONFIRM']))
		{
			$this->Template->mclass = 'confirm';
			$this->Template->message = $_SESSION['UNSUBSCRIBE_CONFIRM'];
			$_SESSION['UNSUBSCRIBE_CONFIRM'] = '';
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
		$this->Template->email = urldecode($this->Input->get('email'));
		$this->Template->submit = specialchars($GLOBALS['TL_LANG']['MSC']['na_unsubscribe']);
		$this->Template->action = ampersand($this->Environment->request);
		$this->Template->formId = 'tl_unsubscribe';
	}
	
	
	/**
	 * Send unsubscribe mail
	 *//*
	private function unsubscribeRecipient()
	{
		
		
		// Check for (valid) e-mail address
		if (!preg_match('/^\w+([_\.-]*\w+)*@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $this->Input->post('email')))
		{
			$_SESSION['SUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}
		
		$objEmail = $this->Database->prepare("SELECT active FROM tl_news_recipients WHERE email=? AND pid IN (" . implode(',', $arrArchives) . ")")
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
	}
*/

	/**
	 * Add a new recipient
	 */
	private function removeRecipient()
	{
		global $objPage;
		$arrArchives = $this->Input->post('archive');
		
		// Visitor must at least check one archive
		if (!is_array($arrArchives) || count($arrArchives) < 1)
		{
			$this->log('No news-alert channel selected', 'ModuleSubscribe addRecipient()', 5);
			$this->reload();
		}

		if (!preg_match('/^\w+([_\.-]*\w+)*@\w+([_\.-]*\w+)*\.[a-z]{2,6}$/i', $this->Input->post('email')))
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['email'];
			$this->reload();
		}

		$objEmail = $this->Database->prepare("SELECT * FROM tl_news_recipients WHERE email=? AND pid IN (" . implode(',', $arrArchives) . ")")
								   ->limit(1)
								   ->execute($this->Input->post('email'));

		if ($objEmail->numRows < 1)
		{
			$_SESSION['UNSUBSCRIBE_ERROR'] = $GLOBALS['TL_LANG']['ERR']['unsubscribed'];
			$this->reload();
		}

		$this->Database->prepare("DELETE FROM tl_news_recipients WHERE email=? AND pid IN (" . implode(',', $arrArchives) . ")")
					   ->execute($this->Input->post('email'));

		// Confirmation e-mail
		$objEmail = new Email();

		$arrTitles = $this->Database->prepare("SELECT title FROM tl_news_archive WHERE id IN (" . implode(',', $arrArchives) . ")")
									->execute()
									->fetchEach('title');

		$strText = str_replace('##archives##', '- ' . implode("\n- ", $arrTitles), $this->na_unsubscribe);
		$strText = str_replace('##domain##', $this->Environment->host, $strText);

		$objEmail->from = $GLOBALS['TL_ADMIN_EMAIL'];
		$objEmail->subject = sprintf($GLOBALS['TL_LANG']['MSC']['nl_subject'], $this->Environment->host);
		$objEmail->text = $strText;

		$objEmail->sendTo($this->Input->post('email'));

		// Redirect to jumpTo page
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

		$_SESSION['UNSUBSCRIBE_CONFIRM'] = $GLOBALS['TL_LANG']['MSC']['nl_removed'];
		$this->reload();
	}
}
