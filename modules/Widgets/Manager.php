<?php namespace KodiCMS\Widgets;

use DB;
use Illuminate\Database\Query\Builder;
use KodiCMS\Pages\Model\FrontendPage;

class Manager
{
	/**
	 * @return array
	 */
	public static function getAvailableWidgets()
	{
		return config('widgets', []);
	}

	/**
	 * Получение списка блоков по умолчанию
	 *
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
	 * @param array $types
	 * @return array
	 */
	public static function getWidgetsByType(array $types = null)
	{
		$query = DB::table('widgets')
			->select('widgets.*')
			->selectRaw('COUNT(page_widgets.page_id) as used')
			->join('page_widgets', 'widgets.id', '=', 'page_widgets.widget_id')
			->groupBy('widgets.id')
			->orderBy('widgets.name');

		if(is_array($types) AND count($types) > 0)
		{
			$query->whereIn('widgets.type', $types);
		}

		return static::makeWidgetsList($query);
	}

	/**
	 * @return array
	 */
	public static function getAllWidgets()
	{
		$query = DB::table('widgets')
			->orderBy('type', 'asc')
			->orderBy('name', 'asc');

		return static::makeWidgetsList($query);
	}

	/**
	 * @param FrontendPage|integer $page
	 * @return array
	 */
	public static function getWidgetsByPage($page)
	{
		if($page instanceof FrontendPage)
		{
			$page = $page->getId();
		}

		$query = DB::table('page_widgets')
			->select('widgets.*', 'page_widgets.block', 'page_widgets.position')
			->join('widgets', 'page_widgets.widget_id', '=', 'widgets.id')
			->where('page_id', (int) $page)
			->orderBy('page_widgets.block', 'asc')
			->orderBy('page_widgets.position', 'asc');

		return static::makeWidgetsList($query);
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function getWidgetById($id)
	{
		$query = DB::table('widgets')
			->where('id', (int) $id)
			->first();

		return static::makeWidget($query);
	}

	/**
	 * @param integer $id
	 * @return array
	 */
	public static function getWidgetLocationById($id)
	{
		$query = DB::table('page_widgets');

		$otherWidgets = []; // занятые блоки для исключения из списков
		$widgetOnPages = []; // выбранные блоки для текущего виджета

		foreach ($query->get() as $row)
		{
			if($row->widget_id == $id)
			{
				$widgetOnPages[$row->page_id] = [$row->block, $row->position, $row->set_crumbs];
			}
			else
			{
				$otherWidgets[$row->page_id][$row->block] = [$row->block, $row->position, $row->set_crumbs];
			}
		}

		return [$widgetOnPages, $otherWidgets];
	}

	/**
	 * @param integer $formPageId
	 * @param integer $toPageId
	 */
	public static function copyWidgets($formPageId, $toPageId)
	{
		$subSelect = DB::table('page_widgets as pw1')
			->join('page_widgets as pw2', function($join) use($toPageId) {
				return $join
					->where('pw2.page_id', (int) $toPageId)
					->on('pw1.widget_id', '=', 'pw2.widget_id');
			})
			->where('pw2.page_id', (int) $formPageId)
			->whereNull('pw2.page_id')
			->toSQL();

		DB::statement("INSERT into page_widgets ('page_id', 'widget_id', 'block', 'position') {$subSelect}");
	}

	/**
	 * @param integer $widgetId
	 * @param array $locations
	 */
	public static function placeWidgetsOnPages($widgetId, array $locations)
	{
		DB::table('page_widgets')
			->where('widget_id', (int) $widgetId)
			->delete();

		$insertData = [];
		foreach($locations as $pageId => $options)
		{
			if (is_null(array_get($options, 'block')) || $options['block'] == -1)
			{
				continue;
			}

			$insertData[] = [
				'page_id' => (int) $pageId,
				'widget_id' => (int) $widgetId,
				'block' => $options['block'],
				'position' => (int) array_get($options, 'position'),
				'set_crumbs' => (bool) array_get($options, 'set_crumbs')
			];
		}

		if(count($insertData) > 0)
		{
			DB::table('page_widgets')
				->where('widget_id', (int) $widgetId)
				->insert($insertData);
		}
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
	 *
	 * @param $data
	 * @return WidgetDecorator
	 */
	protected static function makeWidget($data)
	{
		if (empty($data) OR ! self::exists_by_type($data['type']))
		{
			return NULL;
		}

		$widget = Kohana::unserialize($data['code']);
		unset($data['code'], $data['type']);

		foreach ($data as $key => $value)
		{
			$widget->{$key} = $value;
		}

		self::$_cache[$widget->id] = $widget;

		return $widget;
	}
}