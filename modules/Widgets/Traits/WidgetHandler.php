<?php

namespace KodiCMS\Widgets\Traits;

trait WidgetHandler
{
    /**
     * @return string
     */
    public function getHandlerLink()
    {
        return route('widget.handler', [$this->getId()]);
    }
}
