<?php namespace KodiCMS\ModulesLoader\Providers;

use Event;
use Profiler;
use KodiCMS\ModulesLoader\ModulesLoader;
use KodiCMS\ModulesLoader\ModulesFileSystem;
use KodiCMS\ModulesLoader\Console\Commands\ModulesList;

class ModuleServiceProvider extends ServiceProvider
{
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
		$this->app->singleton('modules.loader', function ()
		{
			return new ModulesLoader(config('cms.modules'));
		});

		$this->app->singleton('modules.filesystem', function ($app)
		{
			return new ModulesFileSystem($app['modules.loader'], $app['files']);
		});

		$this->registerConsoleCommand('cms:modules:list', ModulesList::class);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Event::listen('illuminate.query', function($sql, $bindings, $time) {
			$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
			$sql = vsprintf($sql, $bindings);

			Profiler::append('Database', $sql, $time / 1000);
		});
	}
}