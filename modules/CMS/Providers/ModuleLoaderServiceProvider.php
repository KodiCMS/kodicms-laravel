<?php
namespace KodiCMS\CMS\Providers;

use Illuminate\Foundation\AliasLoader;
use KodiCMS\Support\Loader\ModulesLoader;
use KodiCMS\ModulesLoader\ModulesFileSystem;
use KodiCMS\ModulesLoader\ModulesLoaderFacade;
use KodiCMS\ModulesLoader\ModulesFileSystemFacade;
use KodiCMS\ModulesLoader\Providers\ModuleServiceProvider as BaseModuleServiceProvider;

class ModuleLoaderServiceProvider extends BaseModuleServiceProvider
{

    /**
     * Providers to register
     * @var array
     */
    protected $providers = [
        \KodiCMS\Plugins\Providers\PluginServiceProvider::class,
        \KodiCMS\ModulesLoader\Providers\RouteServiceProvider::class,
        EventServiceProvider::class,
        \KodiCMS\CMS\Providers\BusServiceProvider::class,
        \KodiCMS\ModulesLoader\Providers\AppServiceProvider::class,
        \KodiCMS\ModulesLoader\Providers\ConfigServiceProvider::class,
        \KodiCMS\Users\Providers\AuthServiceProvider::class,
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
            return new ModulesLoader(config('cms.modules', []));
        });

        $this->app->singleton('modules.filesystem', function ($app) {
            return new ModulesFileSystem($app['modules.loader'], $app['files']);
        });

        $this->registerAliases();
        $this->registerProviders();

        $this->registerConsoleCommands();
    }


    /**
     * Register aliases
     */
    protected function registerAliases()
    {
        AliasLoader::getInstance([
            'ModulesLoader'     => ModulesLoaderFacade::class,
            'ModulesFileSystem' => ModulesFileSystemFacade::class,
            'Keys'              => \KodiCMS\Support\Facades\KeysHelper::class,
            'RouteAPI'          => \KodiCMS\Support\Facades\RouteAPI::class,
            'CMS'               => \KodiCMS\CMS\CMS::class,
            'DatabaseConfig'    => \KodiCMS\Support\Facades\DatabaseConfig::class,
            'Profiler'          => \KodiCMS\Support\Helpers\Profiler::class,
        ]);
    }
}