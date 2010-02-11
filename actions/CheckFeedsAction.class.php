<?php
class rssreader_CheckFeedsAction extends f_action_BaseAction
{
	
	/**
	 * Returns the rssreader_FeedService to handle documents of type "modules_rssreader/feed".
	 * @return rssreader_FeedService
	 */
	private function getFeedService()
	{
		return rssreader_FeedService::getInstance();
	}
	
	
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		//--------------------
		// check a feed
		if ($request->hasParameter('doCheck'))
		{
			$id = $request->getParameter(K::COMPONENT_ID_ACCESSOR);
			$document = DocumentHelper::getDocumentInstance($id);
			if ($document instanceof rssreader_persistentdocument_feed)
			{
				$data = array();
				$feed = $this->getFeedService()->getFeed($document->getUrl(), array());
				
				if (! is_null($feed))
				{
					$request->setAttribute('message', $document->getLabel());
					$type = $feed->get_feed_type();
					$data[] = '<feedtype>' . $type . '</feedtype>';
					$request->setAttribute('contents', '<contents>' . implode('', $data) . '</contents>');
					
					return self::getSuccessView();
				}
				else
				{
					$data[] = '<message>' . f_Locale::translate("&modules.rssreader.bo.general.feed.Inexitant;") . '</message>';
					$data[] = '<color>red</color>';
					$data[] = "<detail><dt style=\"border-left: 2px solid red; padding-left: 2px; color: navy; margin-top: 10px;\"><strong>" . $document->getLabel() . " : </strong></dt><dd>" . f_Locale::translate("&modules.rssreader.bo.general.feed.detail.inexistant;") . "</dd></detail>";
					
					$request->setAttribute('message', $document->getLabel());
					$request->setAttribute('contents', '<contents>' . implode('', $data) . '</contents>');
				}
			}
			else
			{
				$request->setAttribute('message', sprintf(f_Locale::translate("&modules.rssreader.bo.general.Not-a-feed;"), $id));
			}
			return self::getErrorView();
		}
		
		//--------------------
		// send report by mail
		elseif ($request->hasParameter('doSend'))
		{
			$prefs = ModuleService::getInstance()->getPreferencesDocument('rssreader');
			if (! is_null($prefs))
			{
				$contacts = $prefs->getContactArray();
				if (count($contacts))
				{
					$mails = array();
					foreach ($contacts as $contact)
					{
						$mails[] = implode(',', $contact->getEmailAddresses());
					}
					$receivers = implode(',', $mails);
					$mailboxMessageService = mailbox_MessageService::getInstance();
					$mailMessage = $mailboxMessageService->getNewMailMessage();
					$mailMessage->setSubject(f_Locale::translate("&modules.rssreader.mail.Subject;") . " - " . date_Calendar::now()->toString());
					$mailMessage->setSender(Framework::getNoReplyDefaultEmail());
					$mailMessage->setReceiver($receivers);
					
					$message = $request->getParameter('message');
					$content = '<h1 style="font-family: Trebuchet, Arial, sans-serif; font-size: 90%;">' . f_Locale::translate("&modules.rssreader.mail.Error;") . '</h1>';
					$content .= '<dl style="font-family: Trebuchet, Arial, sans-serif; font-size: 80%;">';
					$content .= $message;
					$content .= '</dl>';
					
					$mailMessage->setHtmlAndTextBody($content);
					$mailboxMessageService->send($mailMessage);
				}
			}
			return View::NONE;
		}
		
		//--------------------
		// display check feeds panel
		else
		{
			
			$ids = $request->getParameter(K::COMPONENT_ID_ACCESSOR);
			
			if (is_null($ids))
			{
				$query = $this->getPersistentProvider()->createQuery('modules_rssreader/feed');
				$results = $query->find();
				$ids = array();
				foreach ($results as $result)
				{
					$ids[] = $result->getId();
				}
			}
			else
			{
				$ids = explode(',', $ids);
			}
			
			$request->setAttribute('ids', $ids);
			$request->setAttribute('nbFeeds', count($ids));
			
			return View::INPUT;
		}
	}
	
	public function getRequestMethods()
	{
		return Request::GET | Request::POST;
	}
}