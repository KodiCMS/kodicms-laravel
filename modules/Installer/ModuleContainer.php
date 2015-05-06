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
	}

	/**
	 * @param Router $router
	 */
	public function loadRoutes(Router $router)
	{
		if (CMS::isInstalled())
		{
			return;
		}

		$this->includeRoutes($router);
	}
}