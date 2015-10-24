<?php namespace Plugins\butschster\News\Providers;

use Plugins\butschster\News\Model\News;
use Plugins\butschster\News\Model\NewsContent;
use Plugins\butschster\News\Observers\NewsObserver;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;
use Plugins\butschster\News\Observers\NewsContentObserver;

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