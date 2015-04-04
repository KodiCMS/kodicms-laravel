<?php namespace KodiCMS\Pages\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\Pages\BehaviorManager;

class ModuleServiceProvider extends BaseServiceProvider {

	public function boot()
	{

	}


	public function register()
	{
		BehaviorManager::init();
	}
}