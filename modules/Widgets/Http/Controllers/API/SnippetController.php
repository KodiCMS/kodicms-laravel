<?php

namespace KodiCMS\Widgets\Http\Controllers\API;

use KodiCMS\Widgets\Model\SnippetCollection;
use KodiCMS\CMS\Http\Controllers\API\AbstractFileController;

class SnippetController extends AbstractFileController
{
    /**
     * @return SnippetCollection
     */
    protected function getCollection()
    {
        return new SnippetCollection();
    }

    /**
     * @return string
     */
    protected function getSectionPrefix()
    {
        return 'snippet';
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    protected function getRedirectToEditUrl($filename)
    {
        return route('backend.snippet.edit', [$filename]);
    }

    public function getList()
    {
        $this->setContent($this->collection->getHTMLSelectChoices());
    }
}
