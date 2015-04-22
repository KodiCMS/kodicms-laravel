<?php namespace KodiCMS\CMS\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->app['module.loader']->bootModules();

		$this->app['cms']->shutdown(function () {
			$this->app['module.loader']->cacheFoundFiles();
		});

		Blade::extend(function ($view, $compiler) {
			$pattern = $compiler->createMatcher('event');
			return preg_replace($pattern, '$1<?php event$2; ?>', $view);
		});

		// TODO: устанавливать отоюражение текущего времени согласно настроек, но при этом должны корректно отображаться даты в формах
		// Carbon::setToStringFormat(config('cms.date_format'));
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['module.loader']->registerModules();
	}

}
