<?php
class rssreader_FeedItem
{
	private $title;
	
	private $permalink;
	
	private $date;
	
	private $description;
	
	/**
	 * @param DOMElement $item
	 */
	public function __construct($item, $richContentLevel)
	{
		$title = $item->getElementsByTagName('title')->item(0);
		if ($title !== null)
		{
			$this->title = $title->textContent;	
		}

		$link = $item->getElementsByTagName('link')->item(0);
		if ($link !== null)
		{
			$this->permalink = trim($link->textContent);	
		}
		else
		{
			$link = $item->getElementsByTagName('guid')->item(0);
			if ($link !== null)
			{
				$this->permalink = trim($link->textContent);	
			}
		}
		
		$date =  $item->getElementsByTagName('pubDate')->item(0);
		if ($date !== null)
		{
			$this->date = $this->parseRSSDate($date->textContent);
		}
		
		$description =  $item->getElementsByTagName('description')->item(0);
		if ($description !== null)
		{
			$this->description = $this->filterContent(trim($description->textContent), $richContentLevel);	
		}
		
	}
		
	public function get_permalink()
	{
		return $this->permalink;
	}
	
	public function get_title()
	{
		return $this->title;
	}
	
	public function get_date()
	{
		return $this->date;
	}
	
	public function get_local_date($format)
	{
		$date = date_Calendar::getInstance(date_Converter::convertDateToLocal($this->date));
		return date_DateFormat::format($date, $format);
	}
	
	public function get_content_for_backoffice()
	{
		$warning = MediaHelper::getIcon('warning','small');
		return '<img src="'.$warning.'" style="border:0; margin-top:-5px;"/>'.f_Locale::translate("&modules.rssreader.bo.general.Not-display-bo;");
		
	}
	
	public function get_content()
	{
		return $this->description;
	}
	
	
	private function filterContent($html, $richContentLevel)
	{
		$tagsSometimes = '<embed><img>';
		$tagsAllowed = '<a><acronym><address><b><blockquote><br><center><cite><code><dd><div><dl><dt><em><fieldset><font><h2><h3><h4><h5><h6><i><li><map><ol><p><pre><q><s><small><span><strike><strong><sub><sup><table><tbody><td><tfoot><th><thead><title><tr><tt><u><ul>';
		$tagmin = '<a><p><br>';

		if($richContentLevel == 'high')
		{
			return strip_tags($html, $tagmin);
		}
		elseif($richContentLevel == 'medium')
		{
			return strip_tags($html, $tagsAllowed);
		}
		elseif($richContentLevel == 'low')
		{
			return strip_tags($html, $tagsAllowed.$tagsSometimes);
		}
		
		return $html;
	}
	
	private function parseRSSDate($string)
	{
		$terms = explode(' ', trim($string));
		if (!is_numeric($terms[0])) {$terms = array_slice($terms, 1);}
		$day = intval($terms[0]);
		$month = array_search($terms[1], array("Jan", "Feb", "Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec")) + 1;
		$year = $terms[2];
		list($hour, $minute, $second) = explode(':', $terms[3] . ':00');
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);		
		return date('Y-m-d H:i:s', $timestamp);		
	}
}
