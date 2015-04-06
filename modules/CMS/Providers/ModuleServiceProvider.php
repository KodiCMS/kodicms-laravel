<?php namespace KodiCMS\CMS\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\CMS\Core;
use KodiCMS\CMS\Loader\ModuleLoader;

class ModuleServiceProvider extends BaseServiceProvider
{
	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('module.loader', function ($app) {
			return new ModuleLoader(config('cms.modules'));
		});

		$this->app->singleton('cms', function ($app) {
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