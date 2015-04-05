<?php namespace KodiCMS\Users\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ModuleServiceProvider extends BaseServiceProvider {

	public function boot()
	{

	}


	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'KodiCMS\Users\Services\Registrar'
		);
	}
}