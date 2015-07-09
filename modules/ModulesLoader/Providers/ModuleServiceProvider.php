<?php namespace KodiCMS\ModulesLoader\Providers;

use Event;
use Profiler;
use KodiCMS\ModulesLoader\ModulesLoader;
use KodiCMS\ModulesLoader\ModulesFileSystem;
use KodiCMS\ModulesLoader\Console\Commands\ModulesList;

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
		$this->registerConsoleCommand('cms:modules:list', ModulesList::class);
	}
}