<?php namespace KodiCMS\Widgets\Manager;

use DB;
use Illuminate\Database\Eloquent\Collection;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Contracts\WidgetCorrupt;
use KodiCMS\Widgets\Model\Widget;

class WidgetManagerDatabase extends WidgetManager
{

	/**
	 * @param array $types
	 * @return array
	 */
	public static function getWidgetsByType(array $types = null)
	{
		$widgets = new Widget;

		if(is_array($types) AND count($types) > 0)
		{
			$widgets->whereIn('widgets.type', $types);
		}

		return static::buildWidgetCollection($widgets->get());
	}

	/**
	 * @return array
	 */
	public static function getAllWidgets()
	{
		$widgets = Widget::all();

		return static::buildWidgetCollection($widgets);
	}

	/**
	 * @param int $pageId
	 * @return array
	 */
	public static function getWidgetsByPage($pageId)
	{
		$widgets = Widget::whereHas('pages', function($q) use($pageId)
		{
			$q->where('pages.id', (int) $pageId);

		})->get();

		return static::buildWidgetCollection($widgets);
	}

	/**
	 * @param int $pageId
	 * @return array
	 */
	public static function getPageWidgetBlocks($pageId)
	{
		$query = DB::table('page_widgets')
			->where('page_id', $pageId)
			->get();

		$data = [];
		foreach($query as $row)
		{
			$data[$row->widget_id] = $row->block;
		}

		return $data;
	}

	/**
	 * @param Collection $widgets
	 * @return array
	 */
	private static function buildWidgetCollection(Collection $widgets)
	{
		return $widgets->map(function($widget)
		{
			return $widget->toWidget();
		})->filter(function($widget)
		{
			return !($widget instanceof WidgetCorrupt);
		})->toArray();
	}

	/**
	 * @param $id
	 * @return mixed
	 */
	public static function getWidgetById($id)
	{
		return Widget::find($id)->toWidget();
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
	 * // TODO: добавить установку хлебных крошек
	 *
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
}