<?php

namespace KodiCMS\Pages\Providers;

use WYSIWYG;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Listeners\PlacePagePartsToBlocksEventHandler;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    /**
     * Register any other events for your application.
     *
     * @param  DispatcherContract $events
     *
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        $events->listen('config.loaded', function () {
            BehaviorManager::init();
        });

        app('view')->addNamespace('layouts', layouts_path());

        $events->listen('view.page.edit', function ($page) {
            WYSIWYG::loadAllEditors();
            echo view('pages::parts.list')->with('page', $page);
        }, 999);

        $events->listen('frontend.found', function ($page) {
            $this->app->singleton('frontpage', function () use ($page) {
                return $page;
            });
        }, 9999);

        $events->listen('frontend.found', function (FrontendPage $page) {
            app('assets.meta')->setMetaData($page);
        }, 8000);

        $events->listen('frontend.found', PlacePagePartsToBlocksEventHandler::class, 7000);
    }
}
