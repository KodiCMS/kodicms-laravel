<?php namespace KodiCMS\Pages;

use CMS;
use Route;
use Illuminate\Routing\Router;
use KodiCMS\ModulesLoader\ModuleContainer as BaseModuleContainer;

class ModuleContainer extends BaseModuleContainer
{
	/**
	 * @param Router $router
	 */
	protected function loadSystemRoutes(Router $router)
	{
		if (!CMS::isInstalled())
		{
			return;
		}

		Route::before(function()
		{
			Route::get('{slug}', ['as' => 'frontend.url', 'uses' => 'KodiCMS\Pages\Http\Controllers\FrontendController@run'])
				->where('slug', '(.*)?');
		});
	}
}