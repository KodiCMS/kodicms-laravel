<?php namespace KodiCMS\API;

use KodiCMS\API\Console\Commands\GenerateKey;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

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