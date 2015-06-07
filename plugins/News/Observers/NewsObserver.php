<?php namespace Plugins\News\Observers;

use Plugins\News\Model\News;

class NewsObserver
{
	/**
	 * @param \Plugins\News\Model\News $news
	 * @return void
	 */
	public function creating($news)
	{
		$news->user()->associate(auth()->user());
	}

	/**
	 * @param \Plugins\News\Model\News $news
	 * @return void
	 */
	public function deleted($news)
	{
		$news->content()->delete();
	}
}