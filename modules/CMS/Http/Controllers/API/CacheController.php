<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use Artisan;
use KodiCMS\API\Http\Controllers\System\Controller;

class CacheController extends Controller
{
    public function deleteClear()
    {
        Artisan::call('cache:clear');
        $this->setMessage(trans('cms::core.messages.cache_clear'));
    }
}
