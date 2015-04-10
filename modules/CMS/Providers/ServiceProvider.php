<?php namespace KodiCMS\CMS\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
	/**
	 * Registers a new console (artisan) command
	 * @param $key The command name
	 * @param $class The command class
	 * @return void
	 */
	public function registerConsoleCommand($key, $class)
	{
		$key = 'command.' . $key;
		$this->app[$key] = $this->app->share(function ($app) use ($class)
		{
			return new $class;
		});

		$this->commands($key);
	}

}
