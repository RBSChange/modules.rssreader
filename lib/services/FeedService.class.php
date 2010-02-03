<?php
class rssreader_FeedService extends f_persistentdocument_DocumentService
{
	/**
	 * @var rssreader_FeedService
	 */
	private static $instance;

	private $cachePath = 'rss';

	/**
	 * @return rssreader_FeedService
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
	 * @return rssreader_persistentdocument_feed
	 */
	public function getNewDocumentInstance()
	{
		return $this->getNewDocumentInstanceByModelName('modules_rssreader/feed');
	}

	/**
	 * Create a query based on 'modules_rssreader/feed' model
	 * @return f_persistentdocument_criteria_Query
	 */
	public function createQuery()
	{
		return $this->pp->createQuery('modules_rssreader/feed');
	}

	
	private function getCachepath()
	{
		$cachepath = f_util_FileUtils::buildCachePath('rss');
		f_util_FileUtils::mkdir($cachepath);
		return $cachepath;
	}
	
	
	/**
	 * Return feed content
	 *
	 * @param String $url
	 * @param Array<String> $params
	 * @return rssreader_FeedChannel or null if error
	 */
	public function getFeed($url, $params = array(), $discover = true)
	{
		$cache = isset($params['cache']) ? intval($params['cache']) : 0;
		$richContentLevel = isset($params['richContent']) ? $params['richContent'] : 'none';
		
		if ($cache > 0)
		{
			$cachePath = $this->getCachepath();
			$fileCacheName = f_util_FileUtils::buildPath($cachePath, md5($url) . '.xml');
			if (!file_exists($fileCacheName) || (filemtime($fileCacheName) + ($cache * 3600)) < time())
			{
				$data = $this->getDataContent($url);
				if (file_exists($fileCacheName))
				{
					unlink($fileCacheName);
				}
				if (!empty($data))
				{
					file_put_contents($fileCacheName, $data);
				}
			}
			else 
			{
				$data = file_get_contents($fileCacheName);
			}
		}
		else 
		{
			$data = $this->getDataContent($url);
		}
		
		if (!empty($data))
		{
			return new rssreader_FeedChannel($data, $richContentLevel);
		}
		return null;
	}
	
	private function getDataContent($url)
	{
		$document = new DOMDocument();
		$document->load($url);
		return $document->saveXML();
	}

	/**
	 * returns feeds in specific folder
	 *
	 * @param String $folderId
	 * @return Array
	 */
	public function getFeedsInFolderId($folderId)
	{
		$query = $this->pp->createQuery('modules_rssreader/feed');
		$query->add(Restrictions::childOf($folderId));
		$results = $query->find();
		return $results;
	}

	public function clearAllRssFilesCache()
	{
		$files = glob(f_util_FileUtils::buildPath($this->getCachepath() , '*'));
		foreach ($files as $file)
		{
			@unlink($file);
		}
	}
}