<?php namespace KodiCMS\Datasource\Providers;

use Event;
use Config;
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

	public function boot()
	{
		Event::listen('navigation.init', function(& $items)
		{
			$sections = app('datasource.manager')->getSections();
			$children = [];

			foreach($sections as $section)
			{
				$children[] = [
					'name' => $section['object']->name,
					'label' => $section['object']->name,
					'icon' => 'table',
					'url' => $section['object']->getLink()
				];
			}

			$sitemap = [
				[
					'name' => 'Datasource',
					'label' => 'datasource::core.title.section',
					'icon' => 'tasks',
					'priority' => 100,
					'children' => $children
				]
			];

			$items = array_merge($items, $sitemap);
		});
	}
}