<?php namespace Plugins\News\Providers;

use Plugins\News\Model\News;
use Plugins\News\Model\NewsContent;
use Plugins\News\Observers\NewsContentObserver;
use Plugins\News\Observers\NewsObserver;
use KodiCMS\CMS\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		News::observe(new NewsObserver);
		NewsContent::observe(new NewsContentObserver);
	}

	public function register()
	{

	}
}