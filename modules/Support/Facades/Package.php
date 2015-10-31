<?php
namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;
use KodiCMS\CMS\Assets\Package as Manager;

class Package extends Facade
{

    public static function getFacadeAccessor()
    {
        return Manager::class;
    }
}