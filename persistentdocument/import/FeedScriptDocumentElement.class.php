<?php
class rssreader_FeedScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return rssreader_persistentdocument_feed
     */
    protected function initPersistentDocument()
    {
    	return rssreader_FeedService::getInstance()->getNewDocumentInstance();
    }
}