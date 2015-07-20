<?php namespace KodiCMS\API\Providers;

use Event;
use Illuminate\Routing\Router;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use KodiCMS\API\Console\Commands\GenerateApiKeyCommand;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('cms.api.generate.key', GenerateApiKeyCommand::class);
	}

	public function boot(Router $router)
	{
		Event::listen('view.settings.bottom', function ()
		{
			echo view('api::settings')->render();
		});
	}
}