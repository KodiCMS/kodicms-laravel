<?php namespace KodiCMS\Pages;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

	public function boot()
	{

	}


	public function register()
	{
		BehaviorManager::init();
	}
}