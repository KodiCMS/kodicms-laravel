<?php namespace KodiCMS\Installer\Providers;

use KodiCMS\Installer\Installer;
use Illuminate\Filesystem\Filesystem;
use KodiCMS\Installer\EnvironmentTester;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Installer\Console\Commands\Install;
use KodiCMS\Installer\Console\Commands\ModuleSeed;
use KodiCMS\Installer\Console\Commands\ModuleMigrate;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerConsoleCommand('module.migrate', ModuleMigrate::class);
		$this->registerConsoleCommand('module.seed', ModuleSeed::class);
		$this->registerConsoleCommand('cms.install', Install::class);

		$this->app->singleton('installer', function ($app)
		{
			return new Installer($app['filesystem']);
		});

		$this->app->singleton('installer.environment.tester', function ($app)
		{
			return new EnvironmentTester();
		});
	}
}