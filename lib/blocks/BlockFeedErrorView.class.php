<?php
class rssreader_BlockFeedErrorView extends block_BlockView
{
	/**
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
     * @return void
     */
	public function initialize($context, $request)
	{
		$this->disableCache();
	}


	/**
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
     * @return void
	 */
	public function execute($context, $request)
	{
		$this->setTemplateName('BlockList');
		$this->setAttribute('content', '<h2>'.f_Locale::translate("&modules.rssreader.frontoffice.Feedinvalid;").'</h2>');
	}
}