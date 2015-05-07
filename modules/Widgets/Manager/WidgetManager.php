<?php namespace KodiCMS\Widgets\Manager;

use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;
use KodiCMS\Widgets\Exceptions\Exception;

class WidgetManager implements WidgetManagerInterface
{
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