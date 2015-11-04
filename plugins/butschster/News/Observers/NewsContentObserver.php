<?php

namespace Plugins\butschster\News\Observers;

use WYSIWYG;

class NewsContentObserver
{
    /**
     * @param \Plugins\butschster\News\Model\NewsContent $newsContent
     *
     * @return void
     */
    public function creating($newsContent)
    {
        $editor = WYSIWYG::getDefaultHTMLEditor();

        $newsContent->content_filtered = WYSIWYG::applyFilter($editor, $newsContent->content);
        $newsContent->description_filtered = WYSIWYG::applyFilter($editor, $newsContent->description);
    }
}
