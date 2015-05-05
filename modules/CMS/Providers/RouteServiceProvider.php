<?php namespace KodiCMS\CMS\Providers;

use CMS;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Routing\Router;

class RouteServiceProvider extends BaseRouteServiceProvider
{

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'KodiCMS\CMS\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router $router
	 * @return void
	 */
	public function boot(Router $router)
	{
		parent::boot($router);

	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router $router
	 * @return void
	 */
	public function map(Router $router)
	{
		if (!CMS::isInstalled())
		{
			return;
		}

		foreach ($this->app['module.loader']->getRegisteredModules() as $module)
		{
			$routesFile = $module->getRoutesPath();
			if (is_file($routesFile))
			{
				$router->group(['namespace' => $module->getControllerNamespace()], function ($router) use ($routesFile)
				{
					require $routesFile;
				});
			}
		}
	}
}
