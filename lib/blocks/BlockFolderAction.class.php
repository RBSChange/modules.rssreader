<?php
class rssreader_BlockFolderAction extends block_BlockAction
{
	/**
	 * Enter deiframeion here...
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 * @return void
	 */
	public function initialize($context, $request)
	{
		$this->disableCache();
	}
	
	/**
	 * Récupère l'id de la note a affiché de la QS. Si on visualise la page dans une nouvelle
	 * fenêtre à partir du back office.
	 *
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
	 * @return String view name
	 */
	public function execute($context, $request)
	{
		$folderFeedId = $this->getDocumentIdParameter($context);
		
		
		$feeds = rssreader_FeedService::getInstance()->getFeedsInFolderId($folderFeedId);
		
		if (count($feeds))
		{
			$BO = false;
			
			if (($context->getGlobalRequest()->getParameter('action') == 'PreviewPage' || $context->inBackofficeMode()))
			{
				$BO = true;
			}
			$feedNotPublished = false;
			
			$displayHome = array(false => '', true => 'feedhome');
			$params = array();
			$params['richContent'] = $this->getParameter('richcontent');
			$params['titleOnly'] = $this->getParameter('titleonly') == 'true';
			$params['openWindow'] = $this->getParameter('openwindow') == 'true';
			$params['fusionFeeds'] = $this->getParameter('fusionfeeds') == 'true';
			$params['itemCount'] = $this->getParameter('itemcount');
			$params['classHome'] = $displayHome[$this->getParameter('displayhome') == 'true'];
			
			//-------------------
			// display all feeds merged
			if ($params['fusionFeeds'])
			{
				$params['folderTitle'] = $this->getParameter('foldertitle');
				$fs = rssreader_FeedService::getInstance();
				foreach ($feeds as $feed)
				{
					if ($feed->isPublicated() || $context->inBackofficeMode())
					{
						$url = $feed->getUrl();
						$params['cache'] = $feed->getCacheInHour();
						
						$feeddata = $fs->getFeed($url, $params);
						if (! is_null($feeddata))
						{
							$content[] = $feeddata;
						}
					}
				}
				
				if (count($content) || $context->inBackofficeMode())
				{
					if (count($content))
					{
						$feedContent = rssreader_FeedChannel::merge_items($content);
						array_splice($feedContent, $params['itemCount']);
					}
					else
					{
						$feedNotPublished = true;
					}
					
					$this->setParameter('feed', $feedContent);
					$this->setParameter('params', $params);
					$this->setParameter('backoffice', $BO);
					$this->setParameter('feedNotPublished', $feedNotPublished);
					$this->setParameter('fusion', true);
				}
			
			}
			//-------------------
			// display a list of feeds
			else
			{
				$this->setParameter('feeds', $feeds);
				$this->setParameter('params', $params);
				$this->setParameter('backoffice', $BO);
				$this->setParameter('fusion', false);
			}
			
			return block_BlockView::SUCCESS;
		
		}
		return block_BlockView::NONE;
	}
}
