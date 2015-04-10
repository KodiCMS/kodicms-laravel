<?php namespace KodiCMS\Pages\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\BehaviorManager;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Observers\PageObserver;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		Page::observe(new PageObserver);
		app('view')->addNamespace('frontend', base_path('resources/layouts'));
	}


	public function register()
	{
		BehaviorManager::init();
	}
}