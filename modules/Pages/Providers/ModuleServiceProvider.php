<?php namespace KodiCMS\Pages\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Helpers\Meta;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use Event;
use Blade;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		app('view')->addNamespace('frontend', base_path('resources/layouts'));

		Event::listen('view.page.edit.before', function($page) {
			echo view('pages::parts.list')->with('page', $page);
		});

		Event::listen('frontend.found', function($page) {
			app()->singleton('frontpage.meta', function ($app) use($page) {
				return new Meta($page);
			});
		});

		Blade::extend(function ($view, $compiler) {
			$pattern = $compiler->createMatcher('meta');
			return preg_replace($pattern, '$1<?php meta$2; ?>', $view);
		});

		Blade::extend(function ($view, $compiler) {
			$pattern = $compiler->createMatcher('block');
			return preg_replace($pattern, '$1<?php Block::run$2; ?>', $view);
		});
	}


	public function register()
	{
		Page::observe(new PageObserver);
		PagePart::observe(new PagePartObserver);
		BehaviorManager::init();
	}
}