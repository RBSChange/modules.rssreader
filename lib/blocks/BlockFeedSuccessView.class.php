<?php
class rssreader_BlockFeedSuccessView extends block_BlockView
{
	/**
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
     * @return void
	 */
	public function execute($context, $request)
	{
		$this->setTemplateName('BlockFeed');
		$this->setAttributes($this->getParameters());
	}
}