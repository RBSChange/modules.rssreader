<?php
class rssreader_FeedChannel
{
	private $title;
	
	private $items = array();
	
	/**
	 * @param String $xml
	 */
	public function __construct($xml, $richContentLevel)
	{
		$doc = new DOMDocument();
		$doc->loadXML($xml);
		
		$chanel = $doc->getElementsByTagName('channel')->item(0);
		if ($chanel !== null)
		{
			$title = $chanel->getElementsByTagName('title')->item(0);
			if ($title !== null)
			{
				$this->title = $title->textContent;	
			}
			
			foreach ($chanel->getElementsByTagName('item') as $nodeItem) 
			{
				$item = new rssreader_FeedItem($nodeItem, $richContentLevel);
				$this->items[] = $item;
			}
		}
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_items($start = 0, $count = 0)
	{
		if ($start == 0 && $count == 0)
		{
			return $this->items;
		}
		
		return array_slice($this->items, $start, $count);
	}
	
	public function get_feed_type()
	{
		return 'RSS';
	}
	
	
	public static function merge_items($channelArray)
	{
		$items = null;
		foreach ($channelArray as $channel) 
		{
			if ($items === null)
			{
				$items = $channel->items;
			}
			else
			{
				$items = array_merge($items, $channel->items);
			}
		}
		return $items;
	}
}