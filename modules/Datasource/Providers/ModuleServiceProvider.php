<?php namespace KodiCMS\Datasource\Providers;

use Event;
use KodiCMS\CMS\Navigation\Page;
use KodiCMS\CMS\Navigation\Section;
use KodiCMS\Datasource\FieldManager;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

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
		Event::listen('navigation.inited', function(Section $navigation)
		{
			if (!is_null($section = $navigation->findSection('Datasources')))
			{
				$sections = app('datasource.manager')->getSections();
				foreach($sections as $dsSection)
				{
					$section->addPage(new Page([
						'name' => $dsSection['object']->name,
						'label' => $dsSection['object']->name,
						'icon' => 'table',
						'url' => $dsSection['object']->getLink()
					]));
				}
			}
		});
	}
}