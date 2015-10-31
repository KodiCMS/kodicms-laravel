<?php
namespace KodiCMS\CMS\Providers;

use Blade;
use Cache;
use Config;
use Event;
use Profiler;
use PDOException;
use ModulesFileSystem;
use KodiCMS\Support\Helpers\UI;
use KodiCMS\Support\Helpers\Date;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\Support\Cache\SqLiteTaggedStore;
use KodiCMS\Support\Cache\DatabaseTaggedStore;
use KodiCMS\CMS\Console\Commands\WysiwygListCommand;
use KodiCMS\CMS\Console\Commands\PackagesListCommand;
use KodiCMS\CMS\Console\Commands\ModulePublishCommand;
use KodiCMS\CMS\Console\Commands\ControllerMakeCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocaleDiffCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocalePublishCommand;
use KodiCMS\CMS\Console\Commands\GenerateScriptTranslatesCommand;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerAliases([
            'UI'   => UI::class,
            'Date' => Date::class,
        ]);

        $this->registerProviders([
            GenerateScriptTranslatesCommand::class,
            ModuleLocalePublishCommand::class,
            ModuleLocaleDiffCommand::class,
            ControllerMakeCommand::class,
            ModulePublishCommand::class,
            PackagesListCommand::class,
            WysiwygListCommand::class,
        ]);

        Event::listen('config.loaded', function () {
            if ($this->app->installed()) {
                try {
                    $databaseConfig = new DatabaseConfig;
                    $this->app->instance('config.database', $databaseConfig);

                    $config = $databaseConfig->getAll();
                    foreach ($config as $group => $data) {
                        Config::set($group, array_merge(Config::get($group, []), $data));
                    }
                } catch (PDOException $e) {
                }
            }
        }, 999);

        Event::listen('illuminate.query', function ($sql, $bindings, $time) {
            $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
            $sql = vsprintf($sql, $bindings);

            Profiler::append('Database', $sql, $time / 1000);
        });
    }


    public function boot()
    {
        Blade::directive('event', function ($expression) {
            return "<?php event{$expression}; ?>";
        });

        $this->app->shutdown(function () {
            ModulesFileSystem::cacheFoundFiles();
        });

        Cache::extend('sqlite', function ($app, $config) {
            $connectionName   = array_get($config, 'connection');
            $connectionConfig = config('database.connections.' . $connectionName);

            if ( ! file_exists($connectionConfig['database'])) {
                touch($connectionConfig['database']);
            }

            $connection = $this->app['db']->connection($connectionName);

            return Cache::repository(new SqLiteTaggedStore($connection, $config['schema']));
        });

        Cache::extend('database', function ($app, $config) {
            $connection = $this->app['db']->connection(array_get($config, 'connection'));

            return Cache::repository(new DatabaseTaggedStore($connection, $config['table']));
        });
    }
}