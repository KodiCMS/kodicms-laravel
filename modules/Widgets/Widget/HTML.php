<?php

namespace KodiCMS\Widgets\Widget;

use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Traits\WidgetCache;

class HTML extends Decorator implements WidgetCacheable
{
    use WidgetCache;

    /**
     * @return array
     */
    public function prepareData()
    {
        return [

        ];
    }
}
