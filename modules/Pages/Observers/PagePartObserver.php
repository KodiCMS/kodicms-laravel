<?php

namespace KodiCMS\Pages\Observers;

use Cache;
use WYSIWYG;
use KodiCMS\Pages\Model\PagePart;

class PagePartObserver
{
    /**
     * @param \KodiCMS\Pages\Model\PagePart $part
     *
     * @return bool
     */
    public function saving($part)
    {
        if (is_null($part->wysiwyg)) {
            $part->wysiwyg = config('cms.wysiwyg.default_html_editor');
        }

        if (is_null($part->is_protected)) {
            $part->is_protected = PagePart::PART_NOT_PROTECTED;
        }

        if (is_null($part->name)) {
            $part->name = 'part';
        }

        if (! is_null($part->wysiwyg)) {
            $part->content_html = WYSIWYG::applyFilter($part->wysiwyg, $part->content);
        }

        $this->clearCache($part->page_id);
    }

    /**
     * @param \KodiCMS\Pages\Model\PagePart $part
     */
    public function saved($part)
    {
    }

    /**
     * @param \KodiCMS\Pages\Model\PagePart $part
     */
    public function deleted($part)
    {
        $this->clearCache($part->page_id);
    }

    /**
     * @param int $pageId
     */
    protected function clearCache($pageId)
    {
        Cache::forget("pageParts::{$pageId}");
    }
}
