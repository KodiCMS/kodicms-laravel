<?php

namespace KodiCMS\CMS\Providers;

use Config;
use WYSIWYG;
use Profiler;
use PDOException;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\CMS\Handlers\Events\SettingsSave;
use KodiCMS\CMS\Handlers\Events\SettingsValidate;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'backend.settings.validate' => [SettingsValidate::class],
        'backend.settings.save'     => [SettingsSave::class],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        $events->listen('view.settings.bottom', function () {
            WYSIWYG::loadAllEditors();
            echo view('cms::ace.settings')->with('availableACEThemes', config('cms.wysiwyg.ace_themes'));
        });

        $events->listen('view.menu', function ($navigation) {
            echo view('cms::navigation.list')->with('navigation', $navigation)->render();
        });

        $events->listen('config.loaded', function () {
            if (cms_installed()) {
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

        $events->listen('illuminate.query', function ($sql, $bindings, $time) {
            $sql = str_replace(['%', '?'], ['%%', '%s'], $sql);
            $sql = vsprintf($sql, $bindings);

            Profiler::append('Database', $sql, $time / 1000);
        });
    }
}
