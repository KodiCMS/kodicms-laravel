<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KodiCMS\Pages\Helper\Block
 */
class Block extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'layout.block';
    }
}
