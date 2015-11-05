<?php

namespace KodiCMS\Dashboard\Widget;

use Profiler as ProfilerHelper;

class Profiler extends Decorator
{
    /**
     * @var string
     */
    protected $frontendTemplate = 'dashboard::widgets.profiler.template';

    /**
     * @var array
     */
    protected $size = [
        'x'        => 5,
        'y'        => 2,
        'max_size' => [6, 2],
        'min_size' => [3, 2],
    ];

    /**
     * @return array
     */
    public function prepareData()
    {
        return [
            'stats'            => ProfilerHelper::application(),
            'application_cols' => ['min', 'max', 'average'],
        ];
    }
}
