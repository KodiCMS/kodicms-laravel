<?php namespace KodiCMS\Widgets\Providers;

use Event;
use KodiCMS\Widgets\Contracts\WidgetPaginator;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\CMS\Assets\Package;
use Request;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Collection\PageWidgetCollection;
use KodiCMS\Widgets\Manager\WidgetManager;
use KodiCMS\Widgets\Model\SnippetCollection;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{

	}

	public function boot()
	{
		Page::creating(function($page)
		{
			$postData = Request::input('widgets', []);

			if (!empty($postData['from_page_id']))
			{
				WidgetManagerDatabase::copyWidgets($postData['from_page_id'], $page->id);
			}
		});

		Page::saving(function($page)
		{
			$postData = Request::input('widget', []);

			foreach ($postData as $widgetId => $location)
			{
				WidgetManagerDatabase::updateWidgetOnPage($widgetId, $page->id, $location);
			}
		});

		app('view')->addNamespace('snippets', snippets_path());

		Event::listen('frontend.found', function($page)
		{
			$this->app->singleton('layout.widgets', function($app) use($page)
			{
				return new PageWidgetCollection($page->getId());
			});

			$this->app->singleton('layout.block', function($app) use($page)
			{
				return new Block(app('layout.widgets'));
			});
		}, 9000);

		Event::listen('view.page.edit', function($page)
		{
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

		Event::listen('view.widget.edit', function ($widget)
		{
			// TODO: вынести в виджеты
			if ($widget->isRenderable())
			{
				$commentKeys = WidgetManager::getTemplateKeysByType($widget->type);
				$snippets = (new SnippetCollection())->getHTMLSelectChoices();
				$assetsPackages = Package::getHTMLSelectChoice();

				echo view('widgets::widgets.partials.renderable', compact('widget', 'commentKeys', 'snippets', 'assetsPackages'))->render();
			}

			if ($widget->isCacheable() AND acl_check('widgets.cache'))
			{
				echo view('widgets::widgets.partials.cacheable', compact('widget'))->render();
			}

			if (acl_check('widgets.roles') AND !$widget->isHandler())
			{
				// TODO: добавить загрузку списка ролей
				$usersRoles = [];

				echo view('widgets::widgets.partials.permissions', compact('widget', 'usersRoles'))->render();
			}
		});

		Event::listen('view.widget.edit.settings', function ($widget)
		{
			if($widget->toWidget() instanceof WidgetPaginator)
			{
				echo view('widgets::widgets.paginator.widget', [
					'widget' => $widget->toWidget()
				])
					->render();
			}
		});

		Event::listen('view.widget.edit.footer', function ($widget)
		{
			if($widget->isHandler())
			{
				echo view('widgets::widgets.partials.handler', compact('widget'))
					->render();
			}
		});
	}
}