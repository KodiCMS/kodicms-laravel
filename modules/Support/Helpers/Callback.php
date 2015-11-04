<?php

namespace KodiCMS\Support\Helpers;

use Closure;
use ReflectionMethod;
use KodiCMS\CMS\Exceptions\Exception;

/**
 * Class Callback.
 */
class Callback
{
    /**
     * @param mixed $callback
     * @param array $parameters
     * @param array $binding
     *
     * @return mixed
     */
    public static function invoke($callback, array $parameters = [], array $binding = [])
    {
        if ($callback instanceof Closure) {
            return static::invokeClosure($callback, $parameters);
        } elseif (is_array($callback)) {
            return static::invokeFunction($callback, $parameters, $binding);
        } elseif (strpos($callback, '@') !== false) {
            return static::invokeClassMethod($callback, $parameters);
        } elseif (strpos($callback, '::') === false) {
            return static::invokeReflectionFunction($callback, $parameters);
        } else {
            return static::invokeStaticClass($callback, $parameters, $binding);
        }
    }

    /**
     * @param array $callback
     * @param array $parameters
     * @param array $binding
     *
     * @return mixed
     */
    public static function invokeFunction(array $callback, array $parameters = [], array $binding = [])
    {
        foreach ($callback as $i => $value) {
            if (is_string($value) && isset($binding[$value])) {
                $callback[$i] = $binding[$value];
            }
        }

        if (is_null($parameters)) {
            return call_user_func($callback);
        } else {
            return call_user_func_array($callback, $parameters);
        }
    }

    /**
     * @param Closure $callback
     * @param array   $parameters
     *
     * @return mixed
     */
    public static function invokeClosure(Closure $callback, array $parameters = [])
    {
        return call_user_func_array($callback, $parameters);
    }

    /**
     * @param string $callback
     * @param array  $parameters
     *
     * @return mixed
     * @throws \Exception
     */
    public static function invokeClassMethod($callback, array $parameters = [])
    {
        list($class, $method) = explode('@', $callback);
        $instance = app($class);
        if (! method_exists($instance, $method)) {
            throw new Exception('Invalid method '.$method);
        }

        return call_user_func_array([$instance, $method], $parameters);
    }

    /**
     * @param string $callback
     * @param array  $parameters
     * @param array  $binding
     *
     * @return mixed
     */
    public static function invokeStaticClass($callback, array $parameters = null, array $binding = [])
    {
        // Split the class and method of the rule
        list($class, $method) = explode('::', $callback, 2);

        if (isset($binding[$class])) {
            $class = $binding[$class];
        }

        // Use a static method call
        $method = new ReflectionMethod($class, $method);

        if (is_null($parameters)) {
            return $method->invoke(null);
        } else {
            return $method->invokeArgs(null, $parameters);
        }
    }

    /**
     * @param string $callback
     * @param array  $parameters
     *
     * @return mixed
     */
    public static function invokeReflectionFunction($callback, array $parameters = null)
    {
        $class = new ReflectionFunction($callback);

        if (is_null($parameters)) {
            return $class->invoke();
        } else {
            return $class->invokeArgs($parameters);
        }
    }
}
