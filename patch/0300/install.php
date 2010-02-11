<?php
/**
 * rssreader_patch_0300
 * @package modules.rssreader
 */
class rssreader_patch_0300 extends patch_BasePatch
{

	/**
	 * Entry point of the patch execution.
	 */
	public function execute()
	{
		$this->log("Compilation des documents...");
		f_util_System::exec('change.php compile-documents');
		
		$this->log("Mise à jour de la structure de la base...");
		$this->executeSQLQuery("ALTER TABLE `m_rssreader_doc_feed` CHANGE `cacheinhour` `cacheinhour` INT( 11 ) NULL DEFAULT NULL");
		$this->executeSQLQuery("UPDATE `m_rssreader_doc_feed` SET `cacheinhour` = 0 WHERE `cacheinhour` IS NULL OR `cacheinhour` < 0");
	}

	/**
	 * Returns the name of the module the patch belongs to.
	 *
	 * @return String
	 */
	protected final function getModuleName()
	{
		return 'rssreader';
	}

	/**
	 * Returns the number of the current patch.
	 * @return String
	 */
	protected final function getNumber()
	{
		return '0300';
	}
}