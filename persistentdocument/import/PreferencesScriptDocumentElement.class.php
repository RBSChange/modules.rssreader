<?php
class rssreader_PreferencesScriptDocumentElement extends import_ScriptDocumentElement
{
    /**
     * @return rssreader_persistentdocument_preferences
     */
    protected function initPersistentDocument()
    {
    	$document = ModuleService::getInstance()->getPreferencesDocument('rssreader');
    	return ($document !== null) ? $document : rssreader_PreferencesService::getInstance()->getNewDocumentInstance();
    }
}