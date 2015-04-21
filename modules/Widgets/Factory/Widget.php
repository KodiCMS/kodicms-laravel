<?php namespace KodiCMS\Widgets\Factory;

use KodiCMS\Widgets\Contracts\WidgetInterface;
use KodiCMS\Widgets\Exceptions\Exception;

class Widget
{
	/**
	 * @param string $class
	 * @return WidgetInterface
	 * @throws Exception
	 */
	public static function factory($class)
	{
		if (!class_exists($class))
		{
			throw new Exception("Widget {$class} not exists");
		}

		if (!(($widget = new $class) instanceof WidgetInterface))
		{
			throw new Exception("Widget {$class} must be instance of WidgetInterface");
		}

		return new $class;
	}
}