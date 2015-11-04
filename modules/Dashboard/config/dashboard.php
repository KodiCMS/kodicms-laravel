<?php

return [
    'profiler'      => [
        'title' => trans('cms::profiler.title'),
        'class' => '\KodiCMS\Dashboard\Widget\Profiler',
        'icon'  => 'bar-chart',
    ],
    'cache_button'  => [
        'title' => trans('dashboard::types.cache_button.title'),
        'class' => '\KodiCMS\Dashboard\Widget\Cache',
        'icon'  => 'trash-o',
    ],
    'mini_calendar' => [
        'title' => trans('dashboard::types.mini_calendar.title'),
        'class' => '\KodiCMS\Dashboard\Widget\MiniCalendar',
        'icon'  => 'calendar',
    ],
    'kodicms_rss'   => [
        'title' => trans('dashboard::types.kodicms_rss.title'),
        'class' => '\KodiCMS\Dashboard\Widget\KodiCMSRss',
        'icon'  => 'github-alt',
    ],
];
