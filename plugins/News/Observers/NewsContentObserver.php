<?php namespace Plugins\News\Observers;

use WYSIWYG;
use Plugins\News\Model\NewsContent;

class NewsContentObserver
{
	/**
	 * @param \Plugins\News\Model\NewsContent $newsContent
	 * @return void
	 */
	public function creating($newsContent)
	{
		$editor = WYSIWYG::getDefaultHTMLEditor();

		$newsContent->content_filtered = WYSIWYG::applyFilter($editor, $newsContent->content);
		$newsContent->description_filtered = WYSIWYG::applyFilter($editor, $newsContent->description);
	}
}