<?php namespace KodiCMS\CMS\Providers;

use Blade;
use ModuleLoader;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		ModuleLoader::bootModules($this->app);

		$this->app['cms']->shutdown(function ()
		{
			ModuleLoader::cacheFoundFiles();
		});

		Blade::directive('event', function($expression)
		{
			return "<?php event{$expression}; ?>";
		});
	}

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
		ModuleLoader::registerModules($this->app);
	}

}
