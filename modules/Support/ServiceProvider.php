<?php

namespace KodiCMS\Support;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * Registers a new console (artisan) command.
     *
     * @param array|string $class The command class
     *
     * @return void
     */
    public function registerConsoleCommand($class)
    {
        $this->commands($class);
    }

    /**
     * @param array $alias
     */
    public function registerAliases(array $alias)
    {
        AliasLoader::getInstance($alias);
    }

    /**
     * @param array $providers
     */
    public function registerProviders(array $providers)
    {
        foreach ($providers as $providerClass) {
            $this->app->register($providerClass);
        }
    }
}
