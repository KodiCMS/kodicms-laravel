<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetRenderEngine
{
    /**
     * @param Widget $widget
     * @param array  $parameters
     */
    public function __construct(Widget $widget, array $parameters = []);

    /**
     * @return Widget
     */
    public function getWidget();

    public function render();
}
