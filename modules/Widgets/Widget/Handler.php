<?php

namespace KodiCMS\Widgets\Widget;

use KodiCMS\Widgets\Contracts\WidgetHandler;

class Handler extends Decorator implements WidgetHandler
{
    use \KodiCMS\Widgets\Traits\WidgetHandler;

    /**
     * @return array
     */
    public function prepareData()
    {
        return [

        ];
    }
}
