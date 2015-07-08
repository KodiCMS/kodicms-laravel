<?php namespace KodiCMS\CMS\Providers;

use KodiCMS\CMS\Core;
use KodiCMS\CMS\ModulesFileSystem;
use KodiCMS\CMS\Loader\ModulesLoader;

class ModuleServiceProvider extends ServiceProvider
{
	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('modules.loader', function ($app)
		{
			return new ModulesLoader(config('cms.modules'));
		});

		$this->app->singleton('modules.filesystem', function ($app)
		{
			return new ModulesFileSystem($app['modules.loader'], $app['files']);
		});

		$this->app->singleton('cms', function ($app)
		{
			return new Core;
		});
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot(){}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register(){}
}