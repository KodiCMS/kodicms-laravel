<?php
namespace KodiCMS\API\Providers;

use Event;
use Illuminate\Routing\Router;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\API\Console\Commands\GenerateApiKeyCommand;

class ModuleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->registerConsoleCommand(GenerateApiKeyCommand::class);
    }


    /**
     * @param Router $router
     */
    public function boot(Router $router)
    {
        Event::listen('view.settings.bottom', function () {
            echo view('api::settings')->render();
        });
    }
}