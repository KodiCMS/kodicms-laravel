<?php

namespace KodiCMS\CMS;

use Event;
use Route;
use Illuminate\Routing\Router;
use KodiCMS\Support\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        Event::listen('routes.loading', function () {
            Route::group(['namespace' => $this->getControllerNamespace(), 'prefix' => backend_url_segment()], function () {
                Route::get('cms/{file}.{ext}', 'System\VirtualMediaLinksController@find')
                    ->where('file', '.*')
                    ->where('ext', '(css|js|png|jpg|gif|otf|eot|svg|ttf|woff)');
            });
        });
    }
}
