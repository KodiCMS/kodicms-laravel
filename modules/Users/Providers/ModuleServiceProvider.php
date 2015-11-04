<?php

namespace KodiCMS\Users\Providers;

use Event;
use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Users\Observers\RoleObserver;
use KodiCMS\Users\Observers\UserObserver;
use KodiCMS\Users\Reflinks\ReflinksBroker;
use KodiCMS\Users\Reflinks\ReflinkTokenRepository;
use KodiCMS\Users\Console\Commands\DeleteExpiredReflinksCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        User::observe(new UserObserver);
        UserRole::observe(new RoleObserver);

        Event::listen('view.navbar.right.after', function () {
            echo view('users::parts.navbar')->render();
        });

        Event::listen('view.menu', function ($navigation) {
            echo view('users::parts.navigation')->render();
        }, 999);
    }

    public function register()
    {
        $this->registerAliases([
            'ACL'      => \KodiCMS\Support\Facades\ACL::class,
            'Reflinks' => \KodiCMS\Support\Facades\Reflinks::class,
        ]);

        $this->registerReflinksBroker();
        $this->registerTokenRepository();
        $this->registerConsoleCommand(DeleteExpiredReflinksCommand::class);
    }

    /**
     * Register the reflink broker instance.
     *
     * @return void
     */
    protected function registerReflinksBroker()
    {
        $this->app->singleton('reflinks', function ($app) {
            $tokens = $app['reflink.tokens'];

            return new ReflinksBroker($tokens);
        });
    }

    /**
     * Register the token repository implementation.
     * @return void
     */
    protected function registerTokenRepository()
    {
        $this->app->singleton('reflink.tokens', function ($app) {
            $key = $app['config']['app.key'];
            $expire = 60;

            return new ReflinkTokenRepository($key, $expire);
        });
    }
}
