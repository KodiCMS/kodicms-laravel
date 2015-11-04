<?php

namespace KodiCMS\Pages;

use Route;
use Event;
use Illuminate\Routing\Router;
use KodiCMS\Support\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        if (! cms_installed()) {
            return;
        }

        Event::listen('routes.loaded', function () {
            Route::get('{slug}', [
                'as'   => 'frontend.url',
                'uses' => 'KodiCMS\Pages\Http\Controllers\FrontendController@run',
            ])->where('slug', '(.*)?');
        }, 999);
    }
}
