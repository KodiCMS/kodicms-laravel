<?php namespace KodiCMS\Widgets\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use Request;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Collection\PageWidgetCollection;

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
				$collection = new PageWidgetCollection($page->getId());
				$collection->placeWidgetsToLayoutBlocks();

				return new Block($collection);
			});
		}, 9999);

		$events->listen('view.page.edit', function($page) {
			if (acl_check('widgets.index'))
			{
				$collection = new PageWidgetCollection($page->id);

				echo view('widgets::widgets.page.list')
					->with('page', $page)
					->with('pages', PageSitemap::get(true)->exclude([$page->id])->flatten())
					->with('widgetsCollection', $collection)
					->render();
				;
			}
		});

		Page::creating(function($page) {
			$postData = Request::input('widgets', []);

			if (!empty($postData['from_page_id']))
			{
				WidgetManagerDatabase::copyWidgets($postData['from_page_id'], $page->id);
			}
		});

		Page::updating(function($page) {

			$postData = Request::input('widget', []);

			foreach ($postData as $widgetId => $location)
			{
				WidgetManagerDatabase::updateWidgetOnPage($widgetId, $page->id, $location);
			}
		});
	}
}