<?php namespace KodiCMS\CMS\Providers;

use CMS;
use Blade;
use KodiCMS\CMS\Core;
use ModulesFileSystem;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton('cms', function ($app)
		{
			return new Core();
		});
	}

	public function boot()
	{
		Blade::directive('event', function($expression)
		{
			return "<?php event{$expression}; ?>";
		});

		CMS::shutdown(function()
		{
			ModulesFileSystem::cacheFoundFiles();
		});
	}
}