<?php namespace KodiCMS\Widgets\Manager;

use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;

class WidgetManager implements WidgetManagerInterface
{
	/**
	 * Проверка переданного класса на существоавние
	 *
	 * @param string $class
	 * @return bool
	 */
	public static function isClassExists($class)
	{
		return class_exists($class);
	}

	/**
	 * Проверка переданного класса на возможность быть виджетом
	 *
	 * @param string $class
	 * @return bool
	 */
	public static function isWidgetable($class)
	{
		return !static::isCorrupt($class);
	}

	/**
	 * Проверка переданного класса на существование и наличие интерфейса [\KodiCMS\Widgets\Contracts\Widget]
	 *
	 * @param string $class
	 * @return bool
	 */
	public static function isCorrupt($class)
	{
		if(!static::isClassExists($class))
		{
			return true;
		}

		$interfaces = class_implements($class);
		return in_array('KodiCMS\Widgets\Contracts\WidgetCorrupt', $interfaces) or !in_array('KodiCMS\Widgets\Contracts\Widget', $interfaces);
	}

	/**
	 * Проверка переданного класса на возможность быть Виджетом обработчиком
	 *
	 * @param string $class
	 * @return bool
	 */
	public static function isHandler($class)
	{
		return static::isClassExists($class) and in_array('KodiCMS\Widgets\Contracts\WidgetHandler', class_implements($class));
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public static function isRenderable($class)
	{
		return static::isClassExists($class) and in_array('KodiCMS\Widgets\Contracts\WidgetRenderable', class_implements($class));
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public static function isCacheable($class)
	{
		return static::isClassExists($class) and in_array('KodiCMS\Widgets\Contracts\WidgetCacheable', class_implements($class));
	}

	/**
	 * @return array
	 */
	public static function getAvailableTypes()
	{
		$types = [];
		foreach(config('widgets', []) as $group => $widgets)
		{
			foreach($widgets as $type => $widget)
			{
				if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;

				$types[$group][$type] = array_get($widget, 'title', snake_case($type));
			}
		}

		return $types;
	}

	/**
	 * @param string $needleType
	 * @return array
	 */
	public static function getTemplateKeysByType($needleType)
	{
		$class = static::getClassNameByType($needleType);

		if (is_null($class))
		{
			return [];
		}

		$reflector = new \ReflectionClass($class);
		$comments = $reflector->getMethod('prepareData')->getDocComment();

		$keys = [];

		if (!empty($comments))
		{
			$comments = str_replace(['/', '*', "\t", "\n", "\r"], '', $comments);
			preg_match_all("/\[(?s)(?m)(.*)\]/i", $comments, $found);

			if (!empty($found[1]))
			{
				$keys = explode(',', $found[1][0]);
			}
		}

		$keys[] = '[array] $settings';
		$keys[] = '[int] $widgetId';
		$keys[] = '[string] $header';

		return $keys;
	}

	/**
	 * @param string $needleType
	 * @return string|null
	 */
	public static function getClassNameByType($needleType)
	{
		foreach(config('widgets', []) as $group => $widgets)
		{
			foreach($widgets as $type => $widget)
			{
				if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;

				if($type == $needleType)
				{
					return $widget['class'];
				}
			}
		}

		return null;
	}

	/**
	 * @param string $needleClass
	 * @return string|null
	 */
	public static function getTypeByClassName($needleClass)
	{
		foreach(config('widgets', []) as $group => $widgets)
		{
			foreach($widgets as $type => $widget)
			{
				if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;
				if(strpos($widget['class'], $needleClass) !== false)
				{
					return $type;
				}
			}
		}

		return null;
	}

	/**
	 * @param string $type
	 * @param string $name
	 * @param string|null $description
	 * @param array|null $settings
	 * @return Widget|null
	 */
	public static function makeWidget($type, $name, $description = null, array $settings = null)
	{
		$class = static::getClassNameByType($type);

		if (!static::isWidgetable($class))
		{
			return null;
		}

		// TODO: разобраться с этим кодом
//		if (static::isCorrupt($class))
//		{
//			throw new WidgetException("Widget class {$widgetClass} must be implemented of [KodiCMS\Widgets\Contracts\Widget]");
//		}

		$widget = new $class($name, $description);

		if (!is_null($settings))
		{
			$widget->setSettings($settings);
		}

		return $widget;
	}
}