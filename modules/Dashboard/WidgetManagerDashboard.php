<?php namespace KodiCMS\Dashboard;

use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Users\Model\UserMeta;
use KodiCMS\Widgets\Manager\WidgetManager;

class WidgetManagerDashboard extends WidgetManager
{
	public static function getWidgets()
	{
		$widgetsPosition = UserMeta::get(Dashboard::WIDGET_BLOCKS_KEY, []);
		$widgetSettings = UserMeta::get(Dashboard::WIDGET_SETTINGS_KEY, []);

		foreach($widgetsPosition as $i => $data)
		{
			if (!isset($data['widget_id']))
			{
				unset($widgetsPosition[$i]);
				continue;
			}

			$widget = array_get($widgetSettings, $data['widget_id']);

			if (is_array($widget) and is_null($widget = static::toWidget($widget)))
			{
				unset($widgetsPosition[$i]);
				continue;
			}

			if (!($widget instanceof WidgetDashboard))
			{
				unset($widgetsPosition[$i]);
				continue;
			}

			$widgetsPosition[$i]['widget'] = $widget;
		}

		return $widgetsPosition;
	}

	/**
	 * @return array
	 */
	public static function getAvailableTypes()
	{
		$types = [];
		foreach(config('dashboard', []) as $type => $widget)
		{
			if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;

			$types[$type] = $widget;
		}

		return $types;
	}

	/**
	 * @param string $needleType
	 * @return string|null
	 */
	public static function getClassNameByType($needleType)
	{
		foreach(config('dashboard', []) as $type => $widget)
		{
			if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;

			if ($type == $needleType)
			{
				return $widget['class'];
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
		foreach(config('dashboard', []) as $type => $widget)
		{
			if (!isset($widget['class']) or static::isCorrupt($widget['class'])) continue;
			if (strpos($widget['class'], $needleClass) !== false)
			{
				return $type;
			}
		}

		return null;
	}

	/**
	 * @param array $data
	 * @return \KodiCMS\Widgets\Contracts\Widget|null
	 */
	public static function toWidget(array $data)
	{
		extract($data);

		$widget = WidgetManagerDashboard::makeWidget($type, $type, null, $settings);
		$widget->setId($id);

		if (is_array($parameters))
		{
			$widget->setParameters($parameters);
		}


		return $widget;
	}
}