<?php

namespace KodiCMS\CMS\Providers;

use KodiCMS\CMS\CMS;
use KodiCMS\Support\Facades\Wysiwyg;
use KodiCMS\Support\Facades\RouteAPI;
use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Foundation\AliasLoader;
use KodiCMS\Support\Facades\KeysHelper;
use KodiCMS\Assets\AssetsServiceProvider;
use KodiCMS\Support\Loader\ModulesLoader;
use KodiCMS\Support\Facades\DatabaseConfig;
use KodiCMS\ModulesLoader\ModulesFileSystem;
use KodiCMS\ModulesLoader\ModulesLoaderFacade;
use KodiCMS\Users\Providers\AuthServiceProvider;
use KodiCMS\ModulesLoader\ModulesFileSystemFacade;
use KodiCMS\Plugins\Providers\PluginServiceProvider;
use KodiCMS\ModulesLoader\Providers\AppServiceProvider;
use KodiCMS\ModulesLoader\Providers\RouteServiceProvider;
use KodiCMS\ModulesLoader\Providers\ConfigServiceProvider;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseModuleServiceProvider;

class ModuleLoaderServiceProvider extends BaseModuleServiceProvider
{
    /**
     * Providers to register.
     * @var array
     */
    protected $providers = [
        PluginServiceProvider::class,
        RouteServiceProvider::class,
        EventServiceProvider::class,
        AppServiceProvider::class,
        ConfigServiceProvider::class,
        AuthServiceProvider::class,
        AssetsServiceProvider::class,
    ];

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('modules.loader', function () {
            return new ModulesLoader(config('app.modules', []));
        });

        $this->app->singleton('modules.filesystem', function ($app) {
            return new ModulesFileSystem($app['modules.loader'], $app['files']);
        });

        $this->registerAliases();
        $this->registerProviders();

        $this->registerConsoleCommands();
    }

    /**
     * Register aliases.
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance([
            'ModulesLoader'     => ModulesLoaderFacade::class,
            'ModulesFileSystem' => ModulesFileSystemFacade::class,
            'Keys'              => KeysHelper::class,
            'RouteAPI'          => RouteAPI::class,
            'CMS'               => CMS::class,
            'DatabaseConfig'    => DatabaseConfig::class,
            'Profiler'          => Profiler::class,
            'WYSIWYG'           => Wysiwyg::class,
        ]);
    }
}
