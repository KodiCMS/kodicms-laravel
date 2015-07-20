<?php namespace KodiCMS\Installer\Providers;

use KodiCMS\Installer\Installer;
use KodiCMS\Installer\EnvironmentTester;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use KodiCMS\Installer\Console\Commands\InstallCommand;
use KodiCMS\Installer\Console\Commands\ModulesSeedCommand;
use KodiCMS\Installer\Console\Commands\ModulesMigrateCommand;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerConsoleCommand('cms.install', InstallCommand::class);
		$this->registerConsoleCommand('modules.seed', ModulesSeedCommand::class);
		$this->registerConsoleCommand('modules.migrate', ModulesMigrateCommand::class);

		$this->app->singleton('installer', function ($app)
		{
			return new Installer($app['files']);
		});

		$this->app->singleton('installer.environment.tester', function ($app)
		{
			return new EnvironmentTester();
		});
	}
}