<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;
use KodiCMS\API\RouteAPI as RouteAPIClass;

class RouteAPI extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return RouteAPIClass::class;
    }
}
