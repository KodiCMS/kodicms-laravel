<?php namespace KodiCMS\Users;

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
		Route::before(function()
		{
			Route::get('reflink/{code}', ['as' => 'reflink', 'uses' => 'KodiCMS\Users\Http\Controllers\ReflinkController@handle'])
				->where('code', '[a-z0-9]+');
		});
	}
}