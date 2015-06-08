<?php namespace KodiCMS\Plugins\Providers;

use KodiCMS\Plugins\Loader\PluginLoader;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Plugins\Loader\PluginInstaller;

class PluginServiceProvider extends ServiceProvider {

	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('plugins.loader', function($app)
		{
			return new PluginLoader($app['files'], base_path('plugins'));
		});
	}

	public function register()
	{
		$this->registerConsoleCommand('console.plugins.list', 'KodiCMS\Plugins\Console\Commands\PluginsList');

		$this->app->singleton('plugin.installer', function($app)
		{
			return new PluginInstaller($app['db'], $app['files']);
		});
	}

	public function boot()
	{
		try
		{
			$this->app['plugins.loader']->init();
		}
		catch(\Exception $e) {}
	}
}