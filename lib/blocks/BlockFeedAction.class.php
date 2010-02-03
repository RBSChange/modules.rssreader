<?php
class rssreader_BlockFeedAction extends block_BlockAction
{
	
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
		if ($this->hasParameter('feedFromFolder') && $this->hasParameter('params'))
		{
			$feedId = $this->getParameter('feedFromFolder');
			$params = $this->getParameter('params');
		}
		else
		{
			$feedId = array_shift($this->getParameter(K::COMPONENT_ID_ACCESSOR));
			$params = array();
		}
		$feed = DocumentHelper::getDocumentInstance($feedId);
		
		if ($feed->isPublicated() || $context->inBackofficeMode())
		{
			if (count($params) == 0)
			{
				$params['richContent'] = $this->getParameter('richcontent');
				$params['titleOnly'] = $this->getParameter('titleonly') == 'true';
				$params['openWindow'] = $this->getParameter('openwindow') == 'true';
				$params['itemCount'] = $this->getParameter('itemcount');
				if ($this->getParameter('displayhome') == 'true')
				{
					$params['classHome'] = 'feedhome';
				}
				else
				{
					$params['classHome'] = '';
				}
			}
			
			$params['cache'] = $feed->getCacheInHour();
			
			$url = $feed->getUrl();
			
			$fs = rssreader_FeedService::getInstance();
			$feedContent = $fs->getFeed($url, $params);
			
			if (is_null($feedContent))
			{
				return block_BlockView::ERROR;
			}
			
			$this->setParameter('feed', $feedContent);
			$this->setParameter('params', $params);
			$this->setParameter('backoffice', $context->inBackofficeMode());
			
			if (! $feed->isPublicated())
			{
				$this->setParameter('feedNotPublished', true);
			}
			return block_BlockView::SUCCESS;
		}
		return block_BlockView::NONE;
	}
}
