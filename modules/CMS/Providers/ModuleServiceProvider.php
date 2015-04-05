<?php namespace KodiCMS\CMS\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\CMS\Loader\ModuleLoader;
use KodiCMS\CMS\Core;

class ModuleServiceProvider extends BaseServiceProvider
{
	public function __construct($app)
	{
		parent::__construct($app);

		$this->app->singleton('module.loader', function ($app) {
			return new ModuleLoader(Config::get('cms.modules'));
		});

		$this->app->singleton('cms', function ($app) {
			return new Core;
		});
	}

	public function boot()
	{
		$this->app['module.loader']->bootModules();

		$this->app['cms']->shutdown(function()
		{
			$this->app['module.loader']->cacheFoundFiles();
		});

		Blade::extend(function($view, $compiler)
		{
			$pattern = $compiler->createMatcher('event');
			return preg_replace($pattern, '$1<?php event$2; ?>', $view);
		});
	}

	public function register()
	{
		$this->app['module.loader']->registerModules();
	}

}