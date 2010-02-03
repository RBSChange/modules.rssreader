<?php
/**
 * rssreader_persistentdocument_preferences
 * @package rssreader
 */
class rssreader_persistentdocument_preferences extends rssreader_persistentdocument_preferencesbase 
{
	/**
	 * @see f_persistentdocument_PersistentDocumentImpl::getLabel()
	 *
	 * @return String
	 */
	public function getLabel()
	{
		return f_Locale::translateUI(parent::getLabel());
	}	
}