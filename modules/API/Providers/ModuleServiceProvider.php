<?php namespace KodiCMS\API\Providers;

use KodiCMS\API\Console\Commands\GenerateKey;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ModuleServiceProvider extends BaseServiceProvider {

	public function boot()
	{

	}


	public function register()
	{
		$this->commands([
			'\KodiCMS\API\Console\Commands\GenerateKey'
		]);
	}

}