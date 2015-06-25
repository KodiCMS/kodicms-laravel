<?php namespace KodiCMS\Pages;

use CMS;
use Route;
use Illuminate\Routing\Router;

class ModuleContainer extends \KodiCMS\CMS\Loader\ModuleContainer
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