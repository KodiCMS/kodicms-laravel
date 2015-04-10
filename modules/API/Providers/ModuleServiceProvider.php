<?php namespace KodiCMS\API\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('api.generate.key', '\KodiCMS\API\Console\Commands\GenerateKey');
	}
}