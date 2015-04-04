<?php namespace KodiCMS\CMS;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\CMS\Support\ModuleLoader;
use KodiCMS\CMS\Core;

class ServiceProvider extends BaseServiceProvider
{
	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('module.loader', function ($app) {
			return new ModuleLoader(Config::get('cms.modules'));
		});

		$this->app->singleton('cms', function ($app) {
			return new Core;
		});
	}

	public function boot()
	{
		$this->app['module.loader']->bootModules();

		$this->app['cms']->shutdown(function()
		{
			$this->app['module.loader']->cacheFoundFiles();
		});
	}


	public function register()
	{
		$this->app['module.loader']->registerModules();
	}

}