<?php
class rssreader_BlockFolderSuccessView extends block_BlockView
{
	/**
	 * @param block_BlockContext $context
	 * @param block_BlockRequest $request
     * @return void
	 */
	public function execute($context, $request)
	{
		if($this->getParameter('fusion')==true)
		{
			$this->setTemplateName('BlockFolder');
			$this->setAttributes($this->getParameters());
		}
		else
		{
			$feeds = $this->getParameter('feeds');
			$params = $this->getParameter('params');
			$content = '';
			foreach ($feeds as $feed)
			{
				$subBlock = $this->getNewBlockInstance()
        ->setPackageName("modules_rssreader")
        ->setType("feed")
        ->setParameter("feedFromFolder" , $feed->getId())
        ->setParameter("params" , $params);
        $content .= $this->forward($subBlock);
			}
			$this->setTemplateName('BlockList');
			$this->setAttribute('content', $content);
		}
	}
}