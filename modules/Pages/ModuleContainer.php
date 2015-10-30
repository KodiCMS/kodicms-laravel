<?php
namespace KodiCMS\Pages;

use App;
use Route;
use Event;
use Illuminate\Routing\Router;
use KodiCMS\ModulesLoader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{

    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        if ( ! App::installed()) {
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