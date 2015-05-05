<?php namespace KodiCMS\Installer;

use CMS;
use Illuminate\Routing\Router;
use KodiCMS\CMS\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
	/**
	 * @return $this
	 */
	public function boot()
	{
		parent::boot();

		if (!CMS::isInstalled())
		{
			app()->call([$this, 'loadRoutes']);
		}

	}

	/**
	 * @param Router $router
	 */
	public function loadRoutes(Router $router)
	{
		$routesFile = $this->getRoutesPath();
		if (is_file($routesFile))
		{
			$router->group(['namespace' => $this->getControllerNamespace()], function ($router) use ($routesFile)
			{
				require $routesFile;
			});
		}
	}
}