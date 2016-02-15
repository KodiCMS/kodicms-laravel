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
use KodiCMS\ModulesLoader\ModulesFileSystemFacade;
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
        10 => RouteServiceProvider::class,
        20 => EventServiceProvider::class,
        30 => AppServiceProvider::class,
        40 => ConfigServiceProvider::class,
        60 => AssetsServiceProvider::class
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
        if (class_exists($provider = '\KodiCMS\Users\Providers\AuthServiceProvider')) {
            $this->providers[50] = $provider;
        }

        if (class_exists($provider = '\KodiCMS\Plugins\Providers\PluginServiceProvider')) {
            $this->providers[0] = $provider;
        }

        ksort($this->providers);

        $this->app->singleton('modules.loader', function () {
            $modules = config('app.modules', []);
            if (file_exists($path = base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'modules.php'))) {
                $modules = array_merge($modules + include $path);
            }

            return new ModulesLoader($modules);
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
