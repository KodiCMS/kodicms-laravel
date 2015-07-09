<?php namespace KodiCMS\Pages\Providers;

use Blade;
use Block;
use Event;
use WYSIWYG;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Helpers\Meta;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Console\Commands\RebuldLayoutBlocks;
use KodiCMS\Pages\Listeners\PlacePagePartsToBlocksEventHandler;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		Event::listen('config.loaded', function()
		{
			BehaviorManager::init();
		});

		app('view')->addNamespace('layouts', layouts_path());

		app()->singleton('frontpage.meta', function ($app)
		{
			return new Meta();
		});

		Event::listen('view.page.edit', function ($page)
		{
			WYSIWYG::loadAllEditors();
			echo view('pages::parts.list')->with('page', $page);
		}, 999);


		Event::listen('frontend.found', function($page)
		{
			app()->singleton('frontpage', function () use ($page)
			{
				return $page;
			});
		}, 9999);

		Event::listen('frontend.found', function($page)
		{
			app('frontpage.meta')->setPage($page, true);

		}, 8000);

		Event::listen('frontend.found', PlacePagePartsToBlocksEventHandler::class, 7000);


		Blade::directive('meta', function($expression)
		{
			return "<?php meta{$expression}; ?>";
		});

		Blade::directive('block', function($expression)
		{
			return "<?php Block::run{$expression}; ?>";
		});

		Blade::directive('part', function($expression)
		{
			return "<?php echo \\KodiCMS\\Pages\\PagePart::getContent{$expression}; ?>";
		});

		Page::observe(new PageObserver);
		PagePartModel::observe(new PagePartObserver);
	}

	public function register()
	{
		$this->registerConsoleCommand('layout.generate.key', RebuldLayoutBlocks::class);
	}
}