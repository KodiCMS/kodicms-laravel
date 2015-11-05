<?php

namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

class ACL extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'acl';
    }
}
