<?php

namespace KodiCMS\CMS\Providers;

use Collective\Bus\Dispatcher;
use KodiCMS\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param  \Collective\Bus\Dispatcher $dispatcher
     *
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        $dispatcher->mapUsing(function ($command) {
            return Dispatcher::simpleMapping(
                $command, 'KodiCMS\CMS\Commands', 'KodiCMS\CMS\Handlers\Commands'
            );
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
