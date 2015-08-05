<?php namespace KodiCMS\Datasource\Providers;

use Event;
use KodiCMS\CMS\Navigation\Page;
use KodiCMS\CMS\Navigation\Section;
use KodiCMS\Datasource\FieldGroupManager;
use KodiCMS\Datasource\FieldManager;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\Datasource\Datatables\SectionDatatables;
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

		$this->app->singleton('datasource.group.manager', function ()
		{
			return new FieldGroupManager(config('field_groups', []));
		});

		$this->registerConsoleCommand('datasource.migrate', '\KodiCMS\Datasource\Console\Commands\DatasourceMigrate');

		$this->app->bind('datatables', function ($app)
		{
			$request = $app->make('\yajra\Datatables\Request');
			return new SectionDatatables($request);
		});
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
					$page = new Page([
						'name' => $dsSection->getName(),
						'label' => $dsSection->getName(),
						'icon' => $dsSection->getIcon(),
						'url' => $dsSection->getLink(),
						'priority' => $dsSection->getMenuPosition()
					]);

					if($dsSection->getSetting('show_in_root_menu'))
					{
						$navigation->addPage($page);
					}
					else
					{
						$section->addPage($page);
					}
				}

				$types = app('datasource.manager')->getAvailableTypes();
				$subSection = new Section([
					'name' => 'Datasource',
					'label' => trans('datasource::core.button.create'),
					'icon' => 'plus',
				]);

				foreach ($types as $type => $object)
				{
					$subSection->addPage(new Page([
						'name' => $object->getTitle(),
						'label' => $object->getTitle(),
						'icon' => $object->getIcon(),
						'url' => $object->getLink()
					]));
				}

				$section->addPage($subSection);
			}
		});
	}
}