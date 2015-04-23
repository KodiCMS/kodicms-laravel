<?php namespace KodiCMS\Pages\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		app('view')->addNamespace('frontend', base_path('resources/layouts'));

		\Event::listen('view.page.edit.before', function($page) {
			echo view('pages::parts.list')->with('page', $page);
		});
	}


	public function register()
	{
		Page::observe(new PageObserver);
		PagePart::observe(new PagePartObserver);
		BehaviorManager::init();
	}
}