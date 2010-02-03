<?php
/**
 * @date Thu, 19 Jul 2007 13:20:31 +0200
 * @author intessit
 */
class rssreader_PreferencesService extends f_persistentdocument_DocumentService
{
	/**
	 * @var rssreader_PreferencesService
	 */
	private static $instance;

	/**
	 * @return rssreader_PreferencesService
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = self::getServiceClassInstance(get_class());
		}
		return self::$instance;
	}

	/**
	 * @return rssreader_persistentdocument_preferences
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_rssreader/preferences');
	}

	/**
	 * Create a query based on 'modules_rssreader/preferences' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_rssreader/preferences');
	}
	
	/**
	 * @param rssreader_persistentdocument_preferences $document
	 * @param Integer $parentNodeId Parent node ID where to save the document (optionnal => can be null !).
	 * @return void
	 */
	protected function preSave($document, $parentNodeId = null)
	{
		$document->setLabel('&modules.rssreader.bo.general.Module-name;');
	}
}