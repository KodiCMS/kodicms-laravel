<?php

namespace KodiCMS\Pages\Http\Controllers;

use KodiCMS\Pages\Model\LayoutCollection;
use KodiCMS\CMS\Http\Controllers\AbstractFileController;

class LayoutController extends AbstractFileController
{
    /**
     * @var array
     */
    protected $editors = null;

    /**
     * @var LayoutCollection
     */
    protected $collection;

    /**
     * @return LayoutCollection
     */
    protected function getCollection()
    {
        return new LayoutCollection();
    }

    /**
     * @return string
     */
    protected function getSectionPrefix()
    {
        return 'layout';
    }
}
