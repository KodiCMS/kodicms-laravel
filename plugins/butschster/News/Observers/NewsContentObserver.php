<?php namespace Plugins\butschster\News\Observers;

use WYSIWYG;
use Plugins\butschster\News\Model\NewsContent;

class NewsContentObserver
{
	/**
	 * @param \Plugins\butschster\News\Model\NewsContent $newsContent
	 * @return void
	 */
	public function creating($newsContent)
	{
		$editor = WYSIWYG::getDefaultHTMLEditor();

		$newsContent->content_filtered = $editor->applyFilter($text) ;
		$newsContent->description_filtered = $editor->applyFilter($text);
	}
}