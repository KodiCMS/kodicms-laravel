<?php namespace KodiCMS\CMS\Helpers;

use ReflectionMethod;

class Callback
{
	/**
	 *
	 * @param mixed $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke($callback, array $params = null)
	{
		if (is_array($callback) OR !is_string($callback))
		{
			if (is_null($params))
			{
				return call_user_func($callback);
			}
			else
			{
				return call_user_func_array($callback, $params);
			}
		}
		elseif (strpos($callback, '::') === false)
		{
			return static::invoke_function($callback, $params);
		}
		else
		{
			return static::invoke_static_class($callback, $params);
		}
	}

	/**
	 *
	 * @param string $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke_static_class($callback, array $params = null)
	{
		// Split the class and method of the rule
		list($class, $method) = explode('::', $callback, 2);

		// Use a static method call
		$method = new ReflectionMethod($class, $method);

		if (is_null($params))
		{
			return $method->invoke(null);
		}
		else
		{
			return $method->invokeArgs(null, $params);
		}
	}

	/**
	 *
	 * @param string $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke_function($callback, array $params = null)
	{
		$class = new ReflectionFunction($callback);

		if (is_null($params))
		{
			return $class->invoke();
		}
		else
		{
			return $class->invokeArgs($params);
		}
	}
}