<?php
namespace Plugins\butschster\News\Observers;

class NewsObserver
{

    /**
     * @param \Plugins\butschster\News\Model\News $news
     *
     * @return void
     */
    public function creating($news)
    {
        $news->user()->associate(auth()->user());
    }


    /**
     * @param \Plugins\butschster\News\Model\News $news
     *
     * @return void
     */
    public function deleted($news)
    {
        $news->content()->delete();
    }
}