<?php

namespace KodiCMS\Widgets\Observers;

use Request;

/**
 * Class WidgetObserver.
 */
class WidgetObserver
{
    /**
     * @param \KodiCMS\Widgets\Model\Widget $ $widget
     *
     * @return void
     */
    public function saving($widget)
    {
        $ids = Request::get('relatedWidgets', []);
        if (($key = array_search($widget->id, $ids)) !== false) {
            unset($ids[$key]);
        }

        $widget->related()->sync($ids);
    }
}
