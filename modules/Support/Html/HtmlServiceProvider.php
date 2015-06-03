<?php namespace KodiCMS\Support\Html;

class HtmlServiceProvider extends \Illuminate\Html\HtmlServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		parent::register();
		$this->app->alias('html', 'KodiCMS\Support\Html\HtmlBuilder');
	}

	/**
	 * Register the HTML builder instance.
	 *
	 * @return void
	 */
	protected function registerHtmlBuilder()
	{
		$this->app->bindShared('html', function($app)
		{
			return new HtmlBuilder($app['url']);
		});
	}
}
