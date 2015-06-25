<?php namespace KodiCMS\API\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('api.generate.key', '\KodiCMS\API\Console\Commands\GenerateKey');
	}

	public function boot()
	{
		Event::listen('view.settings.bottom', function() {
			echo view('api::settings')->render();
		});
	}
}