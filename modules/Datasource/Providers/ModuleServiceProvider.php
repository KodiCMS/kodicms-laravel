<?php namespace KodiCMS\Datasource\Providers;

use Event;
use KodiCMS\CMS\Navigation\Page;
use KodiCMS\CMS\Navigation\Section;
use KodiCMS\Datasource\FieldManager;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\Datasource\Observers\FieldObserver;
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