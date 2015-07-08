<?php namespace KodiCMS\Installer;

use CMS;
use Route;
use Illuminate\Routing\Router;
use KodiCMS\CMS\Loader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
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

	/**
	 * @param Router $router
	 */
	protected function loadSystemRoutes(Router $router)
	{
		if (CMS::isInstalled())
		{
			return;
		}

		Route::before(function()
		{
			Route::group(['namespace' => $this->getControllerNamespace()], function ()
			{
				Route::get('{slug}', [
					'uses' => 'InstallerController@error',
					'as' => 'installer.error'
				])->where('slug', '(.*)?');
			});
		});
	}
}