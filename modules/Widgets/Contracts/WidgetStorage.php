<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetStorage
{
    /**
     * @param Widget $widget
     *
     * @return Widget
     */
    public function create(Widget $widget);

    /**
     * @param Widget $widget
     *
     * @return Widget
     */
    public function update(Widget $widget);

    /**
     * @param Widget $widget
     *
     * @return bool
     */
    public function delete(Widget $widget);
}
