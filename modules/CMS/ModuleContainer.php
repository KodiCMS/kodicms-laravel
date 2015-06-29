<?php namespace KodiCMS\CMS;

use CMS;
use Route;
use Illuminate\Routing\Router;

class ModuleContainer extends Loader\ModuleContainer
{
	/**
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function register($app)
	{
		if (!$this->isRegistered)
		{
			$this->loadSystemRoutes($app['router']);
			$this->isRegistered = true;
		}

		return $this;
	}

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