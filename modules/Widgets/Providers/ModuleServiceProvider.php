<?php namespace KodiCMS\Widgets\Providers;

use Event;
use Blade;
use Package;
use Request;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Helpers\Block;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Widgets\Manager\WidgetManager;
use KodiCMS\Widgets\Model\SnippetCollection;
use KodiCMS\Widgets\Observers\WidgetObserver;
use KodiCMS\Widgets\Contracts\WidgetPaginator;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use KodiCMS\Widgets\Collection\PageWidgetCollection;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{

	}

	public function boot()
	{
		Page::created(function($page)
		{
			$pageId = array_get(Request::get('widgets'), 'from_page_id');

			if (!empty($pageId))
			{
				WidgetManagerDatabase::copyWidgets($pageId, $page->id);
			}
		});

		Page::deleted(function($page)
		{
			WidgetManagerDatabase::deleteWidgetsFromPage($page->id);
		});

		Page::saving(function($page)
		{
			$postData = Request::input('widget', []);

			foreach ($postData as $widgetId => $location)
			{
				if (array_key_exists('block', $location))
				{
					WidgetManagerDatabase::updateWidgetOnPage($widgetId, $page->id, $location);
				}
			}
		});

		app('view')->addNamespace('snippets', snippets_path());

		Event::listen('frontend.found', function($page)
		{
			$this->app->singleton('layout.widgets', function($app) use($page)
			{
				return new PageWidgetCollection($page->getId());
			});

			$block = new Block(app('layout.widgets'));
			$this->app->singleton('layout.block', function($app) use($block)
			{
				return $block;
			});

		}, 9000);

		Event::listen('view.page.create', function($page)
		{
				echo view('widgets::widgets.page.create')
					->with('page', $page)
					->with('pages', $page->getSitemap())
					->render();
		});

		Event::listen('view.page.edit', function($page)
		{
			if (acl_check('widgets.index') and $page->hasLayout())
			{
				echo view('widgets::widgets.page.iframe')->with('page', $page)->render();
			}
		});

		Event::listen('view.widget.edit', function ($widget)
		{
			if ($widget->isRenderable())
			{
				$commentKeys = WidgetManager::getTemplateKeysByType($widget->type);
				$snippets = (new SnippetCollection())->getHTMLSelectChoices();

				echo view('widgets::widgets.partials.renderable', compact(
					'widget', 'commentKeys', 'snippets'
				))->render();
			}

			if ($widget->isCacheable() AND acl_check('widgets.cache'))
			{
				echo view('widgets::widgets.partials.cacheable', compact('widget'))->render();
			}
		});

		Event::listen('view.widget.edit.footer', function ($widget)
		{
			if ($widget->isRenderable())
			{
				$assetsPackages = Package::getHTMLSelectChoice();
				$widgetList = Widget::where('id', '!=', $widget->id)->lists('name', 'id')->all();

				echo view('widgets::widgets.partials.renderable_buttons', compact(
					'widget', 'commentKeys', 'snippets', 'assetsPackages', 'widgetList'
				))->render();
			}

			if (acl_check('widgets.roles') AND !$widget->isHandler())
			{
				$usersRoles = UserRole::lists('name', 'id')->all();
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

		Blade::directive('widget', function($expression)
		{
			return "<?php echo (new \\KodiCMS\\Widgets\\Engine\\WidgetRenderHTML{$expression})->render(); ?>";
		});

		Blade::directive('snippet', function($expression)
		{
			return "<?php echo (new \\KodiCMS\\Widgets\\Model\\SnippetCollection)->findAndRender{$expression}; ?>";
		});

		Widget::observe(new WidgetObserver);
	}
}