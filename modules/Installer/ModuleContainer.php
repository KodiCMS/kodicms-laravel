<?php

namespace KodiCMS\Installer;

use Event;
use Route;
use Illuminate\Routing\Router;
use KodiCMS\Support\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
    /**
     * @param Router $router
     */
    public function loadRoutes(Router $router)
    {
        if (cms_installed()) {
            return;
        }

        $this->includeRoutes($router);
    }

    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        if (cms_installed()) {
            return;
        }

        Event::listen('routes.loaded', function () {
            Route::group(['namespace' => $this->getControllerNamespace()], function () {
                Route::get('{slug}', [
                    'uses' => 'InstallerController@error',
                    'as'   => 'installer.error',
                ])->where('slug', '(.*)?');
            });
        });
    }
}
