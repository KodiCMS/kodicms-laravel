<?php

return [
    'Other' => [
        'html'      => [
            'class' => '\KodiCMS\Widgets\Widget\HTML',
            'title' => trans('widgets::types.html'),
        ],
        'handler'   => [
            'class' => '\KodiCMS\Widgets\Widget\Handler',
            'title' => trans('widgets::types.handler'),
        ],
        'paginator' => [
            'class' => '\KodiCMS\Widgets\Widget\Paginator',
            'title' => trans('widgets::types.paginator.title'),
        ],
    ],
];
