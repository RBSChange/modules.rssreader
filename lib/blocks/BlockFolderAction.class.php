<?php
class rssreader_BlockFolderAction extends website_BlockAction
{
	/**
	 * @see website_BlockAction::execute()
	 *
	 * @param website_BlockActionRequest $request
	 * @param website_BlockActionResponse $response
	 * @return String
	 */
	function execute($request, $response)
	{
		$folderFeedId = $this->getDocumentIdParameter();
		
		$feeds = rssreader_FeedService::getInstance()->getFeedsInFolderId($folderFeedId);
		if (count($feeds) == 0)
		{
			return website_BlockView::NONE;
		}
		
		$BO = $this->isInBackoffice();
		
		$params = array();
		$params['richContent'] = $this->getConfiguration()->getRichcontent();
		$params['titleOnly'] = $this->getConfiguration()->getTitleonly();
		$params['openWindow'] = $this->getConfiguration()->getOpenwindow();
		$params['fusionFeeds'] = $this->getConfiguration()->getFusionfeeds();
		$params['classHome'] = $this->getConfiguration()->getDisplayhome() ? 'feedhome' : '';
		$params['itemCount'] = $this->getConfiguration()->getItemcount();		
		$params['itemCount'] = $params['itemCount'] < 0 ? 0 : $params['itemCount'];
		$channels = array();
		$fs = rssreader_FeedService::getInstance();
		foreach ($feeds as $feed)
		{
			if ($feed->isPublished() || $BO)
			{
				$url = $feed->getUrl();
				$params['cache'] = $feed->getCacheInHour();
				$feedChannel = $fs->getFeed($url, $params);
				if (! is_null($feedChannel))
				{
					if ($params['itemCount'] > 0)
					{
						$feedChannel->sliceItems(0, $params['itemCount']);
					}
					$channels[] = $feedChannel;
				}
			}
		}
		
		if (count($channels) == 0)
		{
			return website_BlockView::NONE;
		}
		
		//-------------------
		// display all feeds merged
		if ($params['fusionFeeds'])
		{
			$params['folderTitle'] = $this->getConfiguration()->getFoldertitle();	
			$channel = rssreader_FeedChannel::merge_items($channels, $params['folderTitle']);
			if ($params['itemCount'] > 0)
			{
						$channel->sliceItems(0, $params['itemCount']);
			}				
			$channels = array($channel);
		}
		
		$request->setAttribute('channels', $channels);
		$request->setAttribute('params', $params);
		$request->setAttribute('backoffice', $BO);
		return website_BlockView::SUCCESS;
	}

}
