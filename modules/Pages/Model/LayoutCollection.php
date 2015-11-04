<?php

namespace KodiCMS\Pages\Model;

use KodiCMS\CMS\Model\FileCollection;

class LayoutCollection extends FileCollection
{
    /**
     * @var string
     */
    protected $fileClass = Layout::class;

    public function __construct()
    {
        parent::__construct(layouts_path(), 'layouts');
    }
}
