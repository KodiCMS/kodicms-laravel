<?php namespace KodiCMS\Widgets\Manager;

use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;
use KodiCMS\Widgets\Exceptions\Exception;

class WidgetManager implements WidgetManagerInterface
{
	/**
	 * @param string $class
	 * @return bool
	 */
	public static function isClassExists($class)
	{
		return class_exists($class);
	}

	/**
	 * @param string $class
	 * @return bool
	 */
	public static function isCorrupt($class)
	{
		return !static::isClassExists($class) or !in_array('KodiCMS\Widgets\Contracts\Widget', class_implements($class));
	}

	/**
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
				if (!isset($widget['class'])) continue;
				$types[$group][$type] = array_get($widget, 'title', snake_case($type));
			}
		}

		return $types;
	}

	/**
	 * @param string $needleType
	 * @return array
	 */
	public function getTemplateKeysByType($needleType)
	{
		$class = static::getClassNameByType($needleType);

		if (is_null($class))
		{
			return [];
		}

		$reflector = new ReflectionClass($class);
		$comments = $reflector->getMethod('getPreparedData')->getDocComment();

		$keys = [];

		if (!empty($comments))
		{
			$comments = str_replace(['/', '*', "\t", "\n", "\r", ' '], '', $comments);
			preg_match_all("/\[(?s)(?m)(.*)\]/i", $comments, $found);

			if (!empty($found[1]))
			{
				$keys = explode(',', $found[1][0]);
			}
		}

		$keys[] = '$parameters';
		$keys[] = '$widgetId';
		$keys[] = '$header';

		return $keys;
	}

	/**
	 * Получение списка блоков по умолчанию
	 * @return array
	 */
	public static function getDefaultBlocks()
	{
		return [
			-1 => __('--- Remove from page ---'),
			0 => __('--- Hide ---'),
			'PRE' => __('Before page render'),
			'POST' => __('After page render')
		];
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
				if (!isset($widget['class'])) continue;

				if($type == $needleType)
				{
					return $widget['class'];
				}
			}
		}

		return null;
	}
}