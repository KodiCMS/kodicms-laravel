<?php namespace KodiCMS\Widgets\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{

	}

	public function boot(DispatcherContract $events)
	{
		app('view')->addNamespace('snippets', snippets_path());

		$events->listen('frontend.found', function($page) {
			$this->app->singleton('layout.block', function($app) use($page)
			{
				return new Block(new PageWidgetCollection($page));
			});
		}, 9999);

		// TODO: реализовать настройку виджетов на странице редактирования "Страницы"
//		$events->listen('view.page.edit', function($page) {
//			if (acl_check('widgets.index'))
//			{
//				echo view('widgets::widgets.partials.page_block')
//					->with('page', $page)
//					->with('pages', PageSitemap::get(true)->exclude([$page->id])->flatten())
//					->with('widgets', WidgetManagerDatabase::getWidgetsByPage($page->id))
//				;
//			}
//		});
	}
}