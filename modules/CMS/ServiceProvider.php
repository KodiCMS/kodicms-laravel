<?php namespace KodiCMS\CMS;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\CMS\Support\ModuleLoader;

class ServiceProvider extends BaseServiceProvider
{
	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('module.loader', function ($app) {
			return new ModuleLoader(Config::get('cms.modules'));
		});
	}

	public function boot()
	{
		$this->app['module.loader']->bootModules();
	}


	public function register()
	{
		$this->app['module.loader']->registerModules();
	}

}