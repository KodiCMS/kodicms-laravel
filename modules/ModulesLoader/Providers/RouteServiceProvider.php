<?php
namespace KodiCMS\ModulesLoader\Providers;

use Event;
use ModulesLoader;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{

    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';


    /**
     * Load the cached routes for the application.
     *
     * @return void
     */
    protected function loadCachedRoutes()
    {
        Event::fire('routes.loading');

        parent::loadCachedRoutes();

        Event::fire('routes.loaded');
    }


    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     *
     * @return void
     */
    public function map(Router $router)
    {
        Event::fire('routes.loading');

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            $this->app->call([$module, 'loadRoutes'], [$router]);
        }

        Event::fire('routes.loaded');
    }
}