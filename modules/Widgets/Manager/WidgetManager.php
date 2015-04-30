<?php namespace KodiCMS\Widgets;

use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetManager as WidgetManagerInterface;
use KodiCMS\Widgets\Exceptions\Exception;

abstract class WidgetManager implements WidgetManagerInterface
{
	/**
	 * @param string $class
	 * @return Widget
	 * @throws Exception
	 */
	public static function make($class)
	{
		if (!class_exists($class))
		{
			throw new Exception("Widget {$class} not exists");
		}

		if (!(($widget = new $class) instanceof Widget))
		{
			throw new Exception("Widget {$class} must be instance of WidgetInterface");
		}

		return new $class;
	}

	/**
	 * @return array
	 */
	public static function getAvailableWidgets()
	{
		return config('widgets', []);
	}

	/**
	 * Получение списка блоков по умолчанию
	 * @return array
	 */
	public static function get_system_blocks()
	{
		return [
			-1 => __('--- Remove from page ---'),
			0 => __('--- Hide ---'),
			'PRE' => __('Before page render'),
			'POST' => __('After page render')
		];
	}

	/**
	 * @param Builder $query
	 * @return array
	 */
	protected static function makeWidgetsList(Builder $query)
	{
		$widgets = [];
		foreach ($query->get() as $id => $widget)
		{
			$widgets[$widget->id] = static::makeWidget($widget);
		}

		return $widgets;
	}

	/**
	 * @param $data
	 * @return WidgetDecorator
	 */
	protected static function makeWidget($data)
	{
		$widget = unserialize($data['code']);
		unset($data['code'], $data['type']);

		foreach ($data as $key => $value)
		{
			$widget->{$key} = $value;
		}

		self::$cache[$widget->id] = $widget;

		return $widget;
	}
}