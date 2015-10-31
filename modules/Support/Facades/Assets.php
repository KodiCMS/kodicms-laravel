<?php
namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;
use KodiCMS\CMS\Assets\Core;

class Assets extends Facade
{

    public static function getFacadeAccessor()
    {
        return Core::class;
    }

}