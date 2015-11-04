<?php

namespace KodiCMS\Installer\Providers;

use KodiCMS\Installer\Installer;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Installer\EnvironmentTester;
use KodiCMS\Installer\Console\Commands\InstallCommand;
use KodiCMS\Installer\Console\Commands\DropDatabaseCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAliases([
            'Installer'         => \KodiCMS\Support\Facades\Installer::class,
            'EnvironmentTester' => \KodiCMS\Support\Facades\EnvironmentTester::class,
        ]);

        $this->registerConsoleCommand([
            InstallCommand::class,
            DropDatabaseCommand::class,
        ]);

        if (! cms_installed()) {
            putenv('APP_ENV=local');
        }

        $this->app->singleton('installer', function ($app) {
            return new Installer($app['files']);
        });

        $this->app->singleton('installer.environment.tester', function ($app) {
            return new EnvironmentTester();
        });
    }
}
