<?php namespace KodiCMS\Installer\Providers;

use KodiCMS\Installer\Console\Commands\InstallCommand;
use KodiCMS\Installer\Console\Commands\ModulesMigrateCommand;
use KodiCMS\Installer\Console\Commands\ModulesSeedCommand;
use KodiCMS\Installer\EnvironmentTester;
use KodiCMS\Installer\Installer;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerConsoleCommand('cms.install', InstallCommand::class);
		$this->registerConsoleCommand('modules.seed', ModulesSeedCommand::class);
		$this->registerConsoleCommand('modules.migrate', ModulesMigrateCommand::class);

		if (!$this->app->installed())
		{
			putenv('APP_ENV=local');
		}

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