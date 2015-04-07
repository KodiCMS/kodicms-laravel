<?php namespace KodiCMS\Pages\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\Pages\BehaviorManager;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Observers\PageObserver;

class ModuleServiceProvider extends BaseServiceProvider {

	public function boot()
	{
		Page::observe(new PageObserver);
	}


	public function register()
	{
		BehaviorManager::init();
	}
}