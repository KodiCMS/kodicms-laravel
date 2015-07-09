<?php namespace KodiCMS\CMS;

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
		Route::before(function()
		{
			Route::group(['namespace' => $this->getControllerNamespace(), 'prefix' => CMS::backendPath()], function ()
			{
				Route::get('cms/{file}.{ext}', 'System\VirtualMediaLinksController@find')
					->where('file', '.*')
					->where('ext', '(css|js|png|jpg|gif|otf|eot|svg|ttf|woff)');
			});
		});
	}
}