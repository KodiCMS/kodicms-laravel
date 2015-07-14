<?php namespace KodiCMS\ModulesLoader\Providers;

use Event;
use ModulesLoader;
use ModulesFileSystem;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		ModulesLoader::registerModules($this->app);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		ModulesLoader::bootModules($this->app);
		ModulesFileSystem::getFoundFilesFromCache();

		Event::listen('app.shutdown', function()
		{
			ModulesFileSystem::cacheFoundFiles();
		});
	}
}
