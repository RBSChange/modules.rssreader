<?php
class rssreader_BlockFeedAction extends website_BlockAction
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
		$feed = $this->getDocumentParameter();		
		if ($feed === null)
		{
			return website_BlockView::NONE;
		}
		
		$BO = ($this->isInBackoffice());	
		if (!$feed->isPublished() && !$BO)
		{
			return website_BlockView::NONE;
		}
		
		$params = array();
		$params['richContent'] = $this->getConfiguration()->getRichcontent();
		$params['titleOnly'] = $this->getConfiguration()->getTitleonly();
		$params['openWindow'] = $this->getConfiguration()->getOpenwindow();
		$params['classHome'] = $this->getConfiguration()->getDisplayhome() ? 'feedhome' : '';
		
		$params['itemCount'] = $this->getConfiguration()->getItemcount();
		$params['itemCount'] = $params['itemCount'] < 0 ? 0 : $params['itemCount'];
		$params['cache'] = $feed->getCacheInHour();
		$url = $feed->getUrl();
		
		$fs = rssreader_FeedService::getInstance();
		$feedChannel = $fs->getFeed($url, $params);
		
		if ($feedChannel === null)
		{
			return website_BlockView::NONE;
		}
		else if ($params['itemCount'] > 0)
		{
			$feedChannel->sliceItems(0, $params['itemCount']);
		}
		
		
		$request->setAttribute('channel', $feedChannel);
		$request->setAttribute('params', $params);
		$request->setAttribute('backoffice', $BO);
		
		return website_BlockView::SUCCESS;
	}
}
