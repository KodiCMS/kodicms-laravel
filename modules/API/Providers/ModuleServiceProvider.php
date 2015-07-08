<?php namespace KodiCMS\API\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\API\Console\Commands\GenerateKey;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('api.generate.key', GenerateKey::class);
	}

	public function boot()
	{
		Event::listen('view.settings.bottom', function ()
		{
			echo view('api::settings')->render();
		});
	}
}