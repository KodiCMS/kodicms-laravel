<?php
namespace KodiCMS\SleepingOwlAdmin\Providers;

use Route;
use KodiCMS\SleepingOwlAdmin\Admin;
use KodiCMS\Support\ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('sleeping_owl.admin', function () {
            return new Admin();
        });

        $this->registerRoutePatterns();

        $this->registerProviders([
            ColumnFilterServiceProvider::class,
            ColumnServiceProvider::class,
            DisplayServiceProvider::class,
            FilterServiceProvider::class,
            FormServiceProvider::class,
            FormItemServiceProvider::class
        ]);
    }

    protected function registerRoutePatterns()
    {
        Route::pattern('adminModelId', '[0-9]+');
        Route::pattern('adminModel', implode('|', $this->app['sleeping_owl.admin']->modelAliases()));
        Route::bind('adminModel', function ($model) {
            $class = array_search($model, $this->app['sleeping_owl.admin']->modelAliases());
            if ($class === false) {
                throw new ModelNotFoundException;
            }

            return $this->app['sleeping_owl.admin']->getModel($class);
        });
        Route::pattern('adminWildcard', '.*');
    }
}
