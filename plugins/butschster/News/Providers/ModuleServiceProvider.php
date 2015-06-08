<?php namespace Plugins\butschster\News\Providers;

use Plugins\butschster\News\Model\News;
use Plugins\butschster\News\Model\NewsContent;
use Plugins\butschster\News\Observers\NewsContentObserver;
use Plugins\butschster\News\Observers\NewsObserver;
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