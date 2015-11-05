<?php

namespace KodiCMS\SleepingOwlAdmin\Facades;

use Illuminate\Support\Facades\Facade;

class SleepingOwlAdmin extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'sleeping_owl.admin';
    }
}
