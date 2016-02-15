<?php

namespace KodiCMS\API\Providers;

use Event;
use KodiCMS\API\RouteApiFacade;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\API\Console\Commands\GenerateApiKeyCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAliases([
            'RouteAPI' => RouteApiFacade::class,
        ]);

        $this->registerConsoleCommand(GenerateApiKeyCommand::class);
    }

    public function boot()
    {
        Event::listen('view.settings.bottom', function () {
            echo view('api::settings')->render();
        });
    }
}
