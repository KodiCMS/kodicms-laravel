<?php

namespace KodiCMS\Datasource\Providers;

use Event;
use KodiCMS\Navigation\Page;
use KodiCMS\Navigation\Section;
use KodiCMS\Navigation\Navigation;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Datasource\FieldManager;
use KodiCMS\Datasource\FieldGroupManager;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\Datasource\Datatables\SectionDatatables;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerAliases([
            'DatasourceManager' => \KodiCMS\Support\Facades\DatasourceManager::class,
            'FieldManager'      => \KodiCMS\Support\Facades\FieldManager::class,
            'FieldGroupManager' => \KodiCMS\Support\Facades\FieldGroupManager::class,
            'SectionDatatables' => \KodiCMS\Datasource\Datatables\SectionDatatables::class,
        ]);

        $this->app->singleton('datasource.manager', function () {
            return new DatasourceManager(config('datasources', []));
        });

        $this->app->singleton('datasource.field.manager', function () {
            return new FieldManager(config('fields', []));
        });

        $this->app->singleton('datasource.group.manager', function () {
            return new FieldGroupManager(config('field_groups', []));
        });

        $this->app->bind('datatables', function ($app) {
            $request = $app->make('\yajra\Datatables\Request');

            return new SectionDatatables($request);
        });

        $this->registerConsoleCommand(\KodiCMS\Datasource\Console\Commands\DatasourceMigrate::class);
    }

    public function boot()
    {
        Event::listen('navigation.inited', function (Navigation $navigation) {
            if (! is_null($section = $navigation->findSectionOrCreate('Datasources'))) {
                $sections = app('datasource.manager')->getSections();

                foreach ($sections as $dsSection) {
                    $page = new Page([
                        'name'     => $dsSection->getName(),
                        'label'    => $dsSection->getName(),
                        'icon'     => $dsSection->getIcon(),
                        'url'      => $dsSection->getLink(),
                        'priority' => $dsSection->getMenuPosition(),
                    ]);

                    if ($dsSection->getSetting('show_in_root_menu')) {
                        $navigation->addPage($page);
                    } else {
                        $section->addPage($page);
                    }
                }

                $types = app('datasource.manager')->getAvailableTypes();

                $subSection = new Section($navigation, [
                    'name'  => 'Datasource',
                    'label' => trans('datasource::core.button.create'),
                    'icon'  => 'plus',
                ]);

                foreach ($types as $type => $object) {
                    $subSection->addPage(new Page([
                        'name'  => $object->getTitle(),
                        'label' => $object->getTitle(),
                        'icon'  => $object->getIcon(),
                        'url'   => $object->getLink(),
                    ]));
                }

                $section->addPage($subSection);
            }
        });
    }
}
