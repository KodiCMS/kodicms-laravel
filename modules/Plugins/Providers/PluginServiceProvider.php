<?php namespace KodiCMS\Plugins\Providers;

use KodiCMS\API\Exceptions\Exception;
use KodiCMS\Plugins\Loader\PluginLoader;
use KodiCMS\CMS\Providers\ServiceProvider;

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