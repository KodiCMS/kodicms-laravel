<?php

namespace KodiCMS\Dashboard\Widget;

class Cache extends Decorator
{
    /**
     * @var string
     */
    protected $frontendTemplate = 'dashboard::widgets.cache_button.template';

    /**
     * @return array
     */
    public function prepareData()
    {
        return [];
    }
}
