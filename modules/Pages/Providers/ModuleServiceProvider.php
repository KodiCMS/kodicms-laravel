<?php
namespace KodiCMS\Pages\Providers;

use Blade;
use Block;
use Event;
use WYSIWYG;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Helpers\Meta;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Observers\PageObserver;
use KodiCMS\Pages\Observers\PagePartObserver;
use KodiCMS\Pages\Model\PagePart as PagePartModel;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\Pages\Console\Commands\RebuildLayoutBlocksCommand;
use KodiCMS\Pages\Listeners\PlacePagePartsToBlocksEventHandler;

class ModuleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        Event::listen('config.loaded', function () {
            BehaviorManager::init();
        });

        app('view')->addNamespace('layouts', layouts_path());

        Event::listen('view.page.edit', function ($page) {
            WYSIWYG::loadAllEditors();
            echo view('pages::parts.list')->with('page', $page);
        }, 999);

        Event::listen('frontend.found', function ($page) {
            $this->app->singleton('frontpage', function () use ($page) {
                return $page;
            });
        }, 9999);

        Event::listen('frontend.found', function (FrontendPage $page) {
            app('assets.meta')->setMetaData($page);
        }, 8000);

        Event::listen('frontend.found', PlacePagePartsToBlocksEventHandler::class, 7000);

        Blade::directive('meta', function ($expression) {
            return "<?php meta{$expression}; ?>";
        });

        Blade::directive('block', function ($expression) {
            return "<?php Block::run{$expression}; ?>";
        });

        Blade::directive('part', function ($expression) {
            return "<?php echo \\KodiCMS\\Pages\\PagePart::getContent{$expression}; ?>";
        });

        Page::observe(new PageObserver);
        PagePartModel::observe(new PagePartObserver);
    }


    public function register()
    {
        $this->registerAliases([
            'Frontpage' => \KodiCMS\Support\Facades\Frontpage::class,
            'Block'     => \KodiCMS\Support\Facades\Block::class,
            'WYSIWYG'   => \KodiCMS\Support\Facades\Wysiwyg::class,
        ]);

        $this->registerConsoleCommand(RebuildLayoutBlocksCommand::class);
    }
}