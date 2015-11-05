<?php

namespace KodiCMS\Widgets\Engine;

use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetRenderEngine;

abstract class WidgetRenderAbstract implements WidgetRenderEngine
{
    /**
     * @var Widget
     */
    protected $widget;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @param Widget $widget
     * @param array  $parameters
     */
    public function __construct(Widget $widget, array $parameters = [])
    {
        $this->setWidget($widget);
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * @return WidgetRenderable
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * @param Widget $widget
     */
    protected function setWidget($widget)
    {
        $this->widget = $widget;
    }
}
