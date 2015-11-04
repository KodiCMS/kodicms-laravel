<?php

namespace KodiCMS\Widgets\Providers;

use Request;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Widgets\Observers\WidgetObserver;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Page::created(function ($page) {
            $pageId = array_get(Request::get('widgets'), 'from_page_id');

            if (! empty($pageId)) {
                WidgetManagerDatabase::copyWidgets($pageId, $page->id);
            }
        });

        Page::deleted(function ($page) {
            WidgetManagerDatabase::deleteWidgetsFromPage($page->id);
        });

        Page::saving(function ($page) {
            $postData = Request::input('widget', []);

            foreach ($postData as $widgetId => $location) {
                if (array_key_exists('block', $location)) {
                    WidgetManagerDatabase::updateWidgetOnPage($widgetId, $page->id, $location);
                }
            }
        });

        app('view')->addNamespace('snippets', snippets_path());

        Widget::observe(new WidgetObserver);
    }

    public function register()
    {
        $this->registerProviders([
            BladeServiceProvider::class,
            EventsServiceProvider::class,
        ]);
    }
}
