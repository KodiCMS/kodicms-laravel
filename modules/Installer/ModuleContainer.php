<?php
namespace KodiCMS\Installer;

use App;
use Event;
use Route;
use Illuminate\Routing\Router;
use KodiCMS\ModulesLoader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{

    /**
     * @param Router $router
     */
    public function loadRoutes(Router $router)
    {
        if (App::installed()) {
            return;
        }

        $this->includeRoutes($router);
    }


    /**
     * @param Router $router
     */
    protected function loadSystemRoutes(Router $router)
    {
        if (App::installed()) {
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