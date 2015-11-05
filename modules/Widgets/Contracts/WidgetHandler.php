<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetHandler extends Widget
{
    /**
     * @return string
     */
    public function getHandlerLink();
}
