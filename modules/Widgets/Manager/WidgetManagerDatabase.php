<?php

namespace KodiCMS\Widgets\Manager;

use DB;
use KodiCMS\Widgets\Model\Widget;

class WidgetManagerDatabase extends WidgetManager
{
    /**
     * @param array $types
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getWidgetsByType(array $types = null)
    {
        $widgets = new Widget;

        if (is_array($types) and count($types) > 0) {
            $widgets->whereIn('widgets.type', $types);
        }

        return static::buildWidgetCollection($widgets->get());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getAllWidgets()
    {
        $widgets = Widget::all();

        return static::buildWidgetCollection($widgets);
    }

    /**
     * @param int $pageId
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getWidgetsByPage($pageId)
    {
        $widgets = Widget::whereHas('pages', function ($q) use ($pageId) {
            $q->where('pages.id', (int) $pageId);

        })->with('related')->get();

        return static::buildWidgetCollection($widgets);
    }

    /**
     * @param int $pageId
     *
     * @return array
     */
    public static function getPageWidgetBlocks($pageId)
    {
        $query = DB::table('page_widgets')->where('page_id', $pageId)->get();

        $data = [];
        foreach ($query as $row) {
            $data[$row->widget_id] = [$row->block, $row->position];
        }

        return $data;
    }

    /**
     * @param $id
     *
     * @return \KodiCMS\Widgets\Contracts\Widget
     */
    public static function getWidgetById($id)
    {
        return Widget::find($id)->toWidget();
    }

    /**
     * @param int $id
     *
     * @return array
     *
     * [
     *    [ // занятые блоки для исключения из списков
     *        (int) {$pageId} => [
     *            (string) {$blockName},
     *            (int) {$position},
     *            (bool) {$isSetCrumbs}
     *        ]
     *    ],
     *    [ // выбранные блоки для текущего виджета
     *        (int) {$pageId} => [
     *            (string){$block} => [
     *                (string) {$blockName},
     *                (int) {$position},
     *                (bool) {$isSetCrumbs}
     *            ]
     *        ]
     *    ]
     * ]
     */
    public static function getWidgetLocationById($id)
    {
        $query = DB::table('page_widgets');

        $otherWidgets = []; // занятые блоки для исключения из списков
        $widgetOnPages = []; // выбранные блоки для текущего виджета

        foreach ($query->get() as $row) {
            if ($row->widget_id == $id) {
                $widgetOnPages[$row->page_id] = [$row->block, $row->position, $row->set_crumbs];
            } else {
                $otherWidgets[$row->page_id][$row->block] = [$row->block, $row->position, $row->set_crumbs];
            }
        }

        return [$widgetOnPages, $otherWidgets];
    }

    /**
     * @param int $formPageId
     * @param int $toPageId
     */
    public static function copyWidgets($formPageId, $toPageId)
    {
        intval($toPageId);
        intval($formPageId);

        $subSelect = DB::table('page_widgets as pw1')->selectRaw("'$toPageId' as page_id, pw1.widget_id, pw1.block, pw1.position, pw1.set_crumbs")->leftJoin('page_widgets as pw2', function (
                $join
            ) {
                return $join->on('pw1.widget_id', '=', 'pw2.widget_id');
            })->where('pw1.page_id', $formPageId)->toSQL();

        DB::statement("INSERT into page_widgets (page_id, widget_id, block, position, set_crumbs) $subSelect", [
            $formPageId,
        ]);
    }

    /**
     * @param int $widgetId
     * @param array   $locations [(int) {pageId} => ['block' => (string) '...', 'position' => (int) '...', 'set_crumbs'
     *                           => (bool) '...']]
     */
    public static function placeWidgetsOnPages($widgetId, array $locations)
    {
        DB::table('page_widgets')->where('widget_id', (int) $widgetId)->delete();

        $insertData = [];
        foreach ($locations as $pageId => $options) {
            if (is_null(array_get($options, 'block')) || $options['block'] == -1) {
                continue;
            }

            $insertData[] = [
                'page_id'    => (int) $pageId,
                'widget_id'  => (int) $widgetId,
                'block'      => $options['block'],
                'position'   => (int) array_get($options, 'position'),
                'set_crumbs' => (bool) array_get($options, 'set_crumbs'),
            ];
        }

        if (count($insertData) > 0) {
            DB::table('page_widgets')->where('widget_id', (int) $widgetId)->insert($insertData);
        }
    }

    /**
     * @param int   $widgetId
     * @param int   $pageId
     * @param array $location ['block' => (string) '...', 'position' => (int) '...', 'set_crumbs' => (bool) '...']
     */
    public static function updateWidgetOnPage($widgetId, $pageId, array $location)
    {
        $query = DB::table('page_widgets')->where('widget_id', (int) $widgetId)->where('page_id', (int) $pageId);

        if ($location['block'] < 0) {
            $query->delete();
        } else {
            $query->update([
                'block'      => $location['block'],
                'position'   => (int) array_get($location, 'position'),
                'set_crumbs' => (bool) array_get($location, 'set_crumbs'),
            ]);
        }
    }

    /**
     * @param int $pageId
     *
     * @return int
     */
    public static function deleteWidgetsFromPage($pageId)
    {
        return DB::table('page_widgets')->where('page_id', (int) $pageId)->delete();
    }
}
