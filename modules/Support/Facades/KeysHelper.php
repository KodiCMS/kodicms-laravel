<?php

namespace KodiCMS\Support\Facades;

use KodiCMS\API\Helpers\Keys;
use Illuminate\Support\Facades\Facade;

class KeysHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Keys::class;
    }
}
