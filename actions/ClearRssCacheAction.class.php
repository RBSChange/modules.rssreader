<?php
class rssreader_ClearRssCacheAction extends rssreader_Action
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		rssreader_FeedService::getInstance()->clearAllRssFilesCache();
		return self::getSuccessView();
	}
}