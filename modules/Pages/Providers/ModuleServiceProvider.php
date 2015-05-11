<?php namespace KodiCMS\Pages\Providers;

use Blade;
use Block;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Helpers\Meta;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\Pages\PagePart;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Pages\Widget\PagePart as PagePartWidget;

class ModuleServiceProvider extends ServiceProvider {

	public function boot(DispatcherContract $events)
	{
		app('view')->addNamespace('layouts', layouts_path());

		app()->singleton('frontpage.meta', function ($app)
		{
			return new Meta();
		});

		$events->listen('view.page.edit', function ($page)
		{
			echo view('pages::parts.list')->with('page', $page);
		}, 999);

		$events->listen('frontend.found', function($page) {
			app('frontpage.meta')->setPage($page, true);

			$layoutBlocks = Block::getLayoutBlocks();

			foreach ($layoutBlocks as $block)
			{
				if (!($part = PagePart::exists($page, $block)))
				{
					continue;
				}

				$partWidget = new PagePartWidget($part['name']);
				$partWidget->setContent($part['content_html']);
				Block::addWidget($partWidget, $block);
			}
		});

		Blade::extend(function ($view, $compiler)
		{
			$pattern = $compiler->createMatcher('meta');

			return preg_replace($pattern, '$1<?php meta$2; ?>', $view);
		});

		Blade::extend(function ($view, $compiler)
		{
			$pattern = $compiler->createMatcher('block');

			return preg_replace($pattern, '$1<?php Block::run$2; ?>', $view);
		});

		Page::observe(new PageObserver);
		PagePartModel::observe(new PagePartObserver);
	}

	public function register()
	{
		$this->registerConsoleCommand('layout.generate.key', '\KodiCMS\Pages\Console\Commands\RebuldLayoutBlocks');

		BehaviorManager::init();
	}
}