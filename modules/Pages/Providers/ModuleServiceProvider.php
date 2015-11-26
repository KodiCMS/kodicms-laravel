<?php

namespace KodiCMS\Pages\Providers;

use Block;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Support\Facades\Frontpage;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Support\Facades\Block as BlockFacade;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\Pages\Console\Commands\RebuildLayoutBlocksCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('view')->addNamespace('layouts', layouts_path());

        Page::observe(new PageObserver);
        PagePartModel::observe(new PagePartObserver);
    }

    /**
     *
     */
    public function register()
    {
        $this->registerAliases([
            'Frontpage' => Frontpage::class,
            'Block'     => BlockFacade::class,
        ]);

        $this->registerProviders([
            BladeServiceProvider::class,
            EventServiceProvider::class,
        ]);

        $this->registerConsoleCommand(RebuildLayoutBlocksCommand::class);
    }
}
