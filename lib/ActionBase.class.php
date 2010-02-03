<?php
class rssreader_ActionBase extends f_action_BaseAction
{
		
	/**
	 * Returns the rssreader_FeedService to handle documents of type "modules_rssreader/feed".
	 *
	 * @return rssreader_FeedService
	 */
	public function getFeedService()
	{
		return rssreader_FeedService::getInstance();
	}
		
	/**
	 * Returns the rssreader_PreferencesService to handle documents of type "modules_rssreader/preferences".
	 *
	 * @return rssreader_PreferencesService
	 */
	public function getPreferencesService()
	{
		return rssreader_PreferencesService::getInstance();
	}
		
}