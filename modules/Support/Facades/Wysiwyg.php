<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;
use KodiCMS\CMS\Wysiwyg\WysiwygManager;

class Wysiwyg extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return WysiwygManager::class;
    }
}
