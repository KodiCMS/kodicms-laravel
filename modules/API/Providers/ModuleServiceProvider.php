<?php namespace KodiCMS\API\Providers;

use Event;
use KodiCMS\API\Console\Commands\GenerateApiKeyCommand;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('cms.api.generate.key', GenerateApiKeyCommand::class);
	}

	public function boot()
	{
		Event::listen('view.settings.bottom', function ()
		{
			echo view('api::settings')->render();
		});
	}
}