<?php

namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\AbstractFileController;
use KodiCMS\Widgets\Model\SnippetCollection;

class SnippetController extends AbstractFileController
{
    /**
     * @var LayoutCollection
     */
    protected $collection;

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
}
