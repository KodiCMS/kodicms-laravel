<?php namespace KodiCMS\Plugins\Providers;

use KodiCMS\Plugins\Model\Plugin;
use KodiCMS\Plugins\Loader\PluginLoader;
use KodiCMS\Plugins\Loader\PluginInstaller;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use KodiCMS\Plugins\Console\Commands\PluginsListCommand;
use KodiCMS\Plugins\Console\Commands\PluginActivateCommand;
use KodiCMS\Plugins\Console\Commands\PluginDeactivateCommand;

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
		$this->app->singleton('plugin.installer', function($app)
		{
			return new PluginInstaller($app['db'], $app['files']);
		});

		try
		{
			Plugin::setConnectionResolver($this->app['db']);
			Plugin::setEventDispatcher($this->app['events']);

			$this->app['plugins.loader']->init();
		}
		catch(\Exception $e) {}

		$this->registerConsoleCommand('cms.plugins.list', PluginsListCommand::class);
		$this->registerConsoleCommand('cms.plugins.activate', PluginActivateCommand::class);
		$this->registerConsoleCommand('cms.plugins.deactivate', PluginDeactivateCommand::class);
	}
}