<?php
namespace KodiCMS\SleepingOwlAdmin;

use Route;
use BadMethodCallException;
use KodiCMS\SleepingOwlAdmin\Providers\RouteServiceProvider;

abstract class AliasBinder
{
    /**
     * Register new alias
     *
     * @param string $alias
     * @param string $class
     */
    public static function register($alias, $class)
    {
        static::$aliases[$alias] = $class;
        Route::group(
            ['prefix' => config('sleeping_owl.prefix'), 'middleware' => config('sleeping_owl.middleware')],
            function () use ($class) {
                call_user_func([$class, 'registerRoutes']);
            }
        );
    }

    /**
     * Get class by alias
     *
     * @param string $alias
     *
     * @return string
     */
    public static function getAlias($alias)
    {
        return static::$aliases[$alias];
    }

    /**
     * Check if alias is registered
     *
     * @param string $alias
     *
     * @return bool
     */
    public static function hasAlias($alias)
    {
        return array_key_exists($alias, static::$aliases);
    }

    /**
     * Create new instance by alias
     *
     * @param string $name
     * @param        $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        if (! static::hasAlias($name)) {
            throw new BadMethodCallException($name);
        }

        return app(static::getAlias($name), $arguments);
    }
}
