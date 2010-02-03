<?php
class rssreader_CheckFeedsInputView extends f_view_BaseView
{

	/**
	 * @param Context $context
	 * @param Request $request
	 */
	public function _execute($context, $request)
	{
		$this->setTemplateName('CheckFeeds', K::XUL);

		//--------------------
		// include js
		$this->getJsService()->registerScript('modules.uixul.lib.default');
		$this->setAttribute('scripts', $this->getJsService()->execute());



		$this->setAttribute('ids', implode(', ', $request->getAttribute('ids')));
		$this->setAttribute('nbFeeds', $request->getAttribute('nbFeeds'));
	}
}
