<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KodiCMS\Datasource\FieldManager
 */
class FieldGroupManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'datasource.group.manager';
    }
}
