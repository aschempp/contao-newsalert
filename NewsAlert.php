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


class NewsAlert extends Frontend
{

	public function __construct()
	{
		return parent::__construct();
	}
	
	
	/**
	 * user clicked the "send" button
	 */
	public function sendButton($dc)
	{
		return $this->send($dc->id, false);
	}
	
	
	/**
	 * Check if news alerts are open to send
	 *
	 * We fetch all news with alert enabled, not sent and date in past
	 * NewsAlert:send will check if the archive is disabled and abort
	 */
	public function cron()
	{
		$objNews = $this->Database->prepare("SELECT * FROM tl_news WHERE newsalert=? AND na_sent=? AND date<=?")
								  ->execute(1, '', time());
								  
		if ($objNews->numRows)
		{
			while( $objNews->next() )
			{
				$this->send($objNews->id, true);
			}
		}
	}
	
	
	/**
	 * Send news alert to the recipients
	 * @param object
	 * @return string
	 */
	public function send($intId, $blnQuiet=false)
	{
		$objNews = $this->Database->prepare("SELECT * FROM tl_news WHERE id=?")
										->limit(1)
										->execute($intId);

		// Return if there is no news or news alert disabled
		if ($objNews->numRows < 1 || $objNews->newsalert != '1')
		{
			return 'NewsAlert disabled';
		}
		
		// Fetch News Archive
		$objArchive = $this->Database->prepare("SELECT * FROM tl_news_archive WHERE id=?")
									 ->limit(1)
									 ->execute($objNews->pid);
									 
		// Return if there is no news archive or news alert disabled
		if ($objArchive->numRows < 1 || $objArchive->newsalert != '1')
		{
			return 'NewsAlert disabled';
		}
		
		$arrAttachments = array();

		// Attachments
		if (!strlen($objNews->teaser) && $objNews->addEnclosure)
		{
			$files = deserialize($objNews->enclosure);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$arrAttachments[] = $file;
					}
				}
			}
		}		

		// Add sender address
		if (!strlen($objArchive->sender))
		{
			$objArchive->sender = $GLOBALS['TL_CONFIG']['adminEmail'];
		}

		$css = '';

		// Add style sheet newsletter.css
		if (file_exists(TL_ROOT . '/newsletter.css'))
		{
			$buffer = file_get_contents(TL_ROOT . '/newsletter.css');
			$buffer = preg_replace('@/\*\*.*\*/@Us', '', $buffer);

			$css  = '<style type="text/css">' . "\n";
			$css .= trim($buffer) . "\n";
			$css .= '</style>' . "\n";
		}

		$content = '';
		
		// Add an image if we send the full news
		if (!strlen($objNews->teaser) && $objNews->addImage && is_file(TL_ROOT . '/' . $objNews->singleSRC))
		{
			$size = deserialize($objNews->size);
			$src = $this->getImage($this->urlEncode($objNews->singleSRC), $size[0], $size[1]);

			if (($imgSize = @getimagesize(TL_ROOT . '/' . $src)) !== false)
			{
				$imgSize = ' ' . $imgSize[3];
			}

			$alt = specialchars($objNews->alt);
			$margin = $this->generateMargin(deserialize($objNews->imagemargin), 'padding');
			$float = in_array($objNews->floating, array('left', 'right')) ? sprintf(' float:%s;', $objNews->floating) : '';
			$caption = $objNews->caption;
			
			$content .= '<div class="image_container"';
			$content .= ($margin || $float) ? ' style="'.$margin . $float.'"' : '';
			$content .= '>';
			$content .= '<img src="'.$src.'"'.$imgSize.' alt="'.$alt.'" />';
			
			if ($this->caption)
			{
				$content .= '<div class="caption">'.$caption.'</div>';
			}

			$content .= '</div>';
		}
		
		// Replace insert tags
		$content .= $this->replaceInsertTags((strlen($objNews->teaser) ? $objNews->teaser : $objNews->text));
		
		// Generate "read on the website" url
		$objTarget = $this->getPageDetails($objArchive->jumpTo);
		if ($objTarget->numRows)
		{
			$strTargetUrl = (strlen($objTarget->domain) ? ('http://'.$objTarget->domain) : $this->Environment->url) . '/' . $this->generateFrontendUrl($objTarget->row(), '/items/' . (strlen($objNews->alias) ? $objNews->alias : $objNews->id));
		}

		// Send news alert
		if ($blnQuiet || (strlen($this->Input->get('token')) && $this->Input->get('token') == $this->Session->get('tl_newsalert_send')))
		{
			// Get total number of recipients
			$objTotal = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news_recipients WHERE pid=? AND active=?")
									   ->execute($objNews->pid, 1);

			// Return if there are no recipients
			if ($objTotal->total < 1)
			{
				if ($blnQuiet)
					return 'No recipients';
					
				$this->Session->set('tl_newsalert_send', null);
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['tl_newsalert']['error'];

				$this->redirect($this->getReferer());
			}

			$intTimeout = ($this->Input->get('timeout') > 0) ? $this->Input->get('timeout') : 1;
			$intStart = $this->Input->get('start') ? $this->Input->get('start') : 0;
			$intPages = $this->Input->get('mpc') ? $this->Input->get('mpc') : 10;

			$objRecipients = $this->Database->prepare("SELECT * FROM tl_news_recipients WHERE pid=? AND active=?");
			
			if (!$blnQuiet)
				$objRecipients->limit($intPages, $intStart);
			
			$objRecipients = $objRecipients->execute($objNews->pid, 1);

			echo '<div style="font-family:Verdana, sans-serif; font-size:11px; line-height:16px; margin-bottom:12px;">';

			// Send news alert
			if ($objRecipients->numRows > 0)
			{
				$objEmail = new Email();

				$objEmail->from = $objArchive->sender;
				$objEmail->subject = strlen($objArchive->subject) ? html_entity_decode(sprintf($objArchive->subject, $objNews->headline)) : html_entity_decode($objNews->headline);

				if (strlen($objArchive->senderName))
				{
					$objEmail->fromName = $objArchive->senderName;
				}

				// Attachments
				if (is_array($arrAttachments) && count($arrAttachments) > 0)
				{
					foreach ($arrAttachments as $strAttachment)
					{
						if (is_file(TL_ROOT . '/' . $strAttachment))
						{
							$objEmail->attachFile(TL_ROOT . '/' . $strAttachment);
						}
					}
				}

				while ($objRecipients->next())
				{
					$strContent = str_replace('##email##', $objRecipients->email, $content);
					$strContent = str_replace('##name##', preg_replace('/@.*$/i', '', $objRecipients->email), $strContent);

					// Send as text
					if ($objArchive->sendText)
					{
						$strContent = preg_replace('@<br( /)?>@i', "\n", $strContent);
						$strContent = strip_tags($strContent);
						$strContent = preg_replace('/\n\n/', "\r\n", $strContent);

						$objEmail->text = $strContent;
					}

					// Send as HTML
					else
					{
						$objTemplate = new FrontendTemplate((strlen($objArchive->na_template) ? $objArchive->na_template : 'mail_default'));

						$objTemplate->title = $objNews->headline;
						$objTemplate->charset = $GLOBALS['TL_CONFIG']['characterSet'];
						$objTemplate->body = $strContent;
						$objTemplate->target = $strTargetUrl;
						$objTemplate->target_label = $GLOBALS['TL_LANG']['MSC']['na_read'];
						$objTemplate->css = $css;

						$objEmail->html = $objTemplate->parse();
						$objEmail->imageDir = TL_ROOT . '/';
					}

					$objEmail->sendTo($objRecipients->email);
					echo 'Sending to <strong>' . $objRecipients->email . '</strong><br />';
				}
			}

			echo '<div style="margin-top:12px;">';

			// Redirect back home
			if ($blnQuiet || $objRecipients->numRows < 1 || ($intStart + $intPages) >= $objTotal->total)
			{
				// Update status
				$this->Database->prepare("UPDATE tl_news SET na_sent=?, na_date=? WHERE id=?")
							   ->execute(1, time(), $objNews->id);
				
				if ($blnQuiet)
					exit;
			
				$this->Session->set('tl_newsalert_send', null);

				$_SESSION['TL_CONFIRM'][] = sprintf($GLOBALS['TL_LANG']['tl_newsalert']['confirm'], $objTotal->total);
				$url = $this->Environment->base . preg_replace('/&(amp;)?(start|mpc|token)=[^&]*/', '', $this->Environment->request);

				echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', 1000);</script>';
				echo '<a href="' . $url . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			// Redirect to the next cycle
			else
			{
				$url = preg_replace('/&(amp;)?(start|mpc)=[^&]*/', '', $this->Environment->request);
				$url = $this->Environment->base . $url . '&start=' . ($intStart + $intPages) . '&mpc=' . $intPages;

				echo '<script type="text/javascript">setTimeout(\'window.location="' . $url . '"\', ' . ($intTimeout * 1000) . ');</script>';
				echo '<a href="' . $url . '">Please click here to proceed if you are not using JavaScript</a>';
			}

			echo '</div></div>';
			exit;
		}
		
		$this->loadLanguageFile('tl_news_archive');

		$strToken = md5(uniqid('', true));
		$this->Session->set('tl_newsalert_send', $strToken);
		$sprintf = strlen($objArchive->senderName) ? $objArchive->senderName . ' &lt;%s&gt;' : '%s';

		// Preview news alert
		return '
<div id="tl_buttons">
<a href="'.$this->getReferer(ENCODE_AMPERSANDS).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_newsalert']['headline'].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->script, ENCODE_AMPERSANDS).'" id="tl_newsalert_send" class="tl_form" method="get">
<div class="tl_formbody_edit tl_newsalert_send">
<input type="hidden" name="do" value="' . $this->Input->get('do') . '" />
<input type="hidden" name="table" value="' . $this->Input->get('table') . '" />
<input type="hidden" name="key" value="' . $this->Input->get('key') . '" />
<input type="hidden" name="id" value="' . $this->Input->get('id') . '" />
<input type="hidden" name="token" value="' . $strToken . '" />
<table cellpadding="0" cellspacing="0" class="prev_header" summary="">
  <tr class="row_0">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsalert']['from'] . '</td>
    <td class="col_1">' . sprintf($sprintf, $objArchive->sender) . '</td>
  </tr>
  <tr class="row_1">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsalert']['subject'] . '</td>
    <td class="col_1">' . $objNews->headline . '</td>
  </tr>
  <tr class="row_2">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_news_archive']['na_template'][0] . '</td>
    <td class="col_1">' . $objArchive->na_template . '</td>
  </tr>' . ((is_array($arrAttachments) && count($arrAttachments) > 0) ? '
  <tr class="row_3">
    <td class="col_0">' . $GLOBALS['TL_LANG']['tl_newsalert']['attachments'] . '</td>
    <td class="col_1">' . implode(', ', $arrAttachments) . '</td>
  </tr>' : '') . '
</table>
<div class="preview">
' . $content . '
</div>
<div class="tl_tbox">
  <h3><label for="ctrl_mpc">' . $GLOBALS['TL_LANG']['tl_news_archive']['mailsPerCycle'][0] . '</label></h3>
  <input type="text" name="mpc" id="ctrl_mpc" value="10" class="tl_text" onfocus="Backend.getScrollOffset();" />' . (($GLOBALS['TL_LANG']['tl_news_archive']['mailsPerCycle'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help">' . $GLOBALS['TL_LANG']['tl_news_archive']['mailsPerCycle'][1] . '</p>' : '') . '
  <h3><label for="ctrl_timeout">' . $GLOBALS['TL_LANG']['tl_news_archive']['timeout'][0] . '</label></h3>
  <input type="text" name="timeout" id="ctrl_timeout" value="1" class="tl_text" onfocus="Backend.getScrollOffset();" />' . (($GLOBALS['TL_LANG']['tl_news_archive']['timeout'][1] && $GLOBALS['TL_CONFIG']['showHelp']) ? '
  <p class="tl_help">' . $GLOBALS['TL_LANG']['tl_news_archive']['timeout'][1] . '</p>' : '') . '
</div>
</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" id="send" class="tl_submit" alt="send newsalert" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_newsalert']['send'][0]).'" /> 
</div>

</div>
</form>';
	}
	

	/**
	 * Return a form to choose a CSV file and import it
	 * @param object
	 * @return string
	 */
	public function importRecipients()
	{
		if ($this->Input->get('key') != 'import')
		{
			return '';
		}

		// Import CSS
		if ($this->Input->post('FORM_SUBMIT') == 'tl_recipients_import')
		{
			if (!$this->Input->post('source') || !is_array($this->Input->post('source')))
			{
				$_SESSION['TL_ERROR'][] = $GLOBALS['TL_LANG']['ERR']['all_fields'];
				$this->reload();
			}

			foreach ($this->Input->post('source') as $strCsvFile)
			{
				$objFile = new File($strCsvFile);

				if ($objFile->extension != 'csv')
				{
					$_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['filetype'], $objFile->extension);
					continue;
				}

				// Get separator
				switch ($this->Input->post('separator'))
				{
					case 'semicolon':
						$strSeparator = ';';
						break;

					case 'tabulator':
						$strSeparator = '\t';
						break;

					case 'linebreak':
						$strSeparator = '\n';
						break;

					default:
						$strSeparator = ',';
						break;
				}

				$strFile = $objFile->getContent();
				$arrRecipients = trimsplit($strSeparator, $strFile);

				foreach ($arrRecipients as $strRecipient)
				{
					$this->Database->prepare("DELETE FROM tl_news_recipients WHERE pid=? AND email=?")->execute($this->Input->get('id'), $strRecipient);
					$this->Database->prepare("INSERT INTO tl_news_recipients SET pid=?, tstamp=?, email=?, active=?")->execute($this->Input->get('id'), time(), $strRecipient, 1);
				}
			}

			setcookie('BE_PAGE_OFFSET', 0, 0, '/');
			$this->redirect(str_replace('&key=import', '', $this->Environment->request));
		}
		
		$this->loadLanguageFile('tl_news_recipients');

		$objTree = new FileTree($this->prepareForWidget($GLOBALS['TL_DCA']['tl_news_recipients']['fields']['source'], 'source', null, 'source', 'tl_news_recipients'));

		// Return form
		return '
<div id="tl_buttons">
<a href="'.ampersand(str_replace('&key=import', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
</div>

<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['tl_news_recipients']['import'][1].'</h2>'.$this->getMessages().'

<form action="'.ampersand($this->Environment->request, ENCODE_AMPERSANDS).'" id="tl_recipients_import" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_recipients_import" />

<div class="tl_tbox">
  <h3><label for="separator">'.$GLOBALS['TL_LANG']['MSC']['separator'][0].'</label></h3>
  <select name="separator" id="separator" class="tl_select" onfocus="Backend.getScrollOffset();">
    <option value="comma">'.$GLOBALS['TL_LANG']['MSC']['comma'].'</option>
    <option value="semicolon">'.$GLOBALS['TL_LANG']['MSC']['semicolon'].'</option>
    <option value="tabulator">'.$GLOBALS['TL_LANG']['MSC']['tabulator'].'</option>
    <option value="linebreak">'.$GLOBALS['TL_LANG']['MSC']['linebreak'].'</option>
  </select>'.(strlen($GLOBALS['TL_LANG']['MSC']['separator'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['MSC']['separator'][1].'</p>' : '').'
  <h3><label for="source">'.$GLOBALS['TL_LANG']['tl_news_recipients']['source'][0].'</label></h3>
'.$objTree->generate().(strlen($GLOBALS['TL_LANG']['tl_news_recipients']['source'][1]) ? '
  <p class="tl_help">'.$GLOBALS['TL_LANG']['tl_news_recipients']['source'][1].'</p>' : '').'
</div>

</div>

<div class="tl_formbody_submit">

<div class="tl_submit_container">
<input type="submit" name="save" id="save" class="tl_submit" alt="import style sheet" accesskey="s" value="'.specialchars($GLOBALS['TL_LANG']['tl_news_recipients']['import'][0]).'" /> 
</div>

</div>
</form>';
	}
	
	
	/**
	 * Synchronize news alert subscription of existing users
	 * @param mixed
	 * @param object
	 * @return mixed
	 */
	public function synchronize($varValue, $objUser)
	{
		$blnIsFrontend = true;

		// If called from the back end, the second argument is a DataContainer object
		if ($objUser instanceof DataContainer)
		{
			$objUser = $this->Database->prepare("SELECT * FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($objUser->id);

			if ($objUser->numRows < 1)
			{
				return $varValue;
			}

			$blnIsFrontend = false;
		}
		
		// Nothing has changed
		if ($varValue == $objUser->newsalert)
		{
			return $varValue;
		}

		$time = time();
		$varValue = deserialize($varValue, true);

		// Get all channel IDs
		$objChannel = $this->Database->execute("SELECT id FROM tl_news_archive");
		$arrChannel = $objChannel->fetchEach('id');

		$arrDelete = array_values(array_diff($arrChannel, $varValue));

		// Delete existing recipients
		if (count($arrDelete))
		{
			$this->Database->prepare("DELETE FROM tl_news_recipients WHERE pid IN(" . implode(',', $arrDelete) . ") AND email=?")
						   ->execute($objUser->email);
		}

		// Add recipients
		foreach ($varValue as $intId)
		{
			$intId = intval($intId);

			if ($intId < 1)
			{
				continue;
			}

			$objRecipient = $this->Database->prepare("SELECT COUNT(*) AS total FROM tl_news_recipients WHERE pid=? AND email=?")
										   ->execute($intId, $objUser->email);

			if ($objRecipient->total < 1)
			{
				$this->Database->prepare("INSERT INTO tl_news_recipients SET pid=?, tstamp=?, email=?, active=1, addedOn=?, ip=?")
							   ->execute($intId, $time, $objUser->email, ($blnIsFrontend ? $time : ''), ($blnIsFrontend ? $this->Environment->ip : ''));
			}
		}

		return serialize($varValue);
	}


	/**
	 * Update a particular member account
	 * @param integer
	 * @param object
	 */
	public function updateAccount()
	{
		if (in_array('newsletter', $this->Config->getActiveModules()))
		{
			$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('newsletter;', 'newsletter,newsalert;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
		}
		else
		{
			$GLOBALS['TL_DCA']['tl_member']['palettes']['default'] = str_replace('assignDir;', 'assignDir;{newsletter_legend:hide},newsalert;', $GLOBALS['TL_DCA']['tl_member']['palettes']['default']);
		}
		
		$intUser = $this->Input->get('id');

		// Front end call
		if (TL_MODE == 'FE')
		{
			$this->import('FrontendUser', 'User');
			$intUser = $this->User->id;
		}

		// Edit account
		if (TL_MODE == 'FE' || $this->Input->get('act') == 'edit')
		{
			$objUser = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				// E-mail address has changed
				if (!empty($_POST) && $this->Input->post('email', true) != $objUser->email)
				{
					$this->Database->prepare("UPDATE tl_news_recipients SET email=? WHERE email=?")
								   ->execute($this->Input->post('email', true), $objUser->email);

					$objUser->email = $this->Input->post('email', true);
				}

				$objSubscriptions = $this->Database->prepare("SELECT pid FROM tl_news_recipients WHERE email=?")
												   ->execute($objUser->email);

				$strNews = serialize($objSubscriptions->fetchEach('pid'));

				$this->Database->prepare("UPDATE tl_member SET newsalert=? WHERE id=?")
							   ->execute($strNews, $intUser);

				// Update the front end user object
				if (TL_MODE == 'FE')
				{
					$this->User->newsalert = $strNews;
				}
			}
		}

		// Delete account
		elseif ($this->Input->get('act') == 'delete')
		{
			$objUser = $this->Database->prepare("SELECT email FROM tl_member WHERE id=?")
									  ->limit(1)
									  ->execute($intUser);

			if ($objUser->numRows)
			{
				$objSubscriptions = $this->Database->prepare("DELETE FROM tl_news_recipients WHERE email=?")
												   ->execute($objUser->email);
			}
		}
	}


	/**
	 * Get all editable news archives and return them as array
	 * @param object
	 * @return array
	 */
	public function getNewsArchives($dc)
	{
		$objNews = $this->Database->execute("SELECT id, title FROM tl_news_archive WHERE newsalert='1'");

		if ($objNews->numRows < 1)
		{
			return array();
		}

		$arrNews = array();

		// Back end
		if (TL_MODE == 'BE')
		{
			while ($objNews->next())
			{
				$arrNews[$objNews->id] = $objNews->title;
			}

			return $arrNews;
		}

		// Front end
		$newsalerts = deserialize($dc->newsalerts, true);

		if (!is_array($newsalerts) || count($newsalerts) < 1)
		{
			return array();
		}

		while ($objNews->next())
		{
			if (in_array($objNews->id, $newsalerts))
			{
				$arrNews[$objNews->id] = $objNews->title;
			}
		}

		return $arrNews;
	}
	
	
	/**
	 * Must be done with onload_callback cause "registration" is alphabetically after "newsalert".
	 */
	public function injectFields()
	{
		$GLOBALS['TL_DCA']['tl_module']['palettes']['personalData'] = str_replace('newsletters;', 'newsletters,newsalerts;', $GLOBALS['TL_DCA']['tl_module']['palettes']['personalData']);
		$GLOBALS['TL_DCA']['tl_module']['palettes']['registration'] = str_replace('newsletters;', 'newsletters,newsalerts;', $GLOBALS['TL_DCA']['tl_module']['palettes']['registration']);
	}
}

