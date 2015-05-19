<?php namespace KodiCMS\Datasource\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\Datasource\FieldManager;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->app->singleton('datasource.manager', function ()
		{
			return new DatasourceManager(config('datasources', []));
		});

		$this->app->singleton('datasource.field.manager', function ()
		{
			return new FieldManager(config('fields', []));
		});

		$this->registerConsoleCommand('datasource.migrate', '\KodiCMS\Datasource\Console\Commands\DatasourceMigrate');
	}
}