<?php

namespace KodiCMS\Widgets\Repository;

use DB;
use KodiCMS\Widgets\Model\Widget;
use KodiCMS\CMS\Repository\BaseRepository;

class WidgetRepository extends BaseRepository
{
    /**
     * @param Widget $model
     */
    public function __construct(Widget $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required|max:255',
            'type' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required|max:255',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        return parent::update($id, array_except($data, ['type']));
    }

    /**
     * @param $pageId
     *
     * @return array
     */
    public function getByPageId($pageId)
    {
        intval($pageId);

        $query = DB::table('page_widgets')->select('widget_id');

        if ($pageId > 0) {
            $query->where('page_id', $pageId);
        }

        $ids = $query->lists('widget_id');

        $widgetList = $this->model->newQuery();

        if (count($ids) > 0) {
            $widgetList->whereNotIn('id', $ids);
        }

        $widgets = [];

        foreach ($widgetList->get() as $widget) {
            if ($widget->isCorrupt() or $widget->isHandler()) {
                continue;
            }

            $widgets[$widget->getType()][$widget->id] = $widget;
        }

        return $widgets;
    }
}
