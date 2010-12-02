<?php
class rssreader_ClearRssCacheAction extends f_action_BaseJSONAction
{
	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		rssreader_FeedService::getInstance()->clearAllRssFilesCache();
		return $this->sendJSON(array('message' => LocaleService::getInstance()->transBO('m.rssreader.bo.actions.cache-deleted')));
	}
}