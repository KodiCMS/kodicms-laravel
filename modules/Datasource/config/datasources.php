<?php

return [
    'default'    => [
        'class' => KodiCMS\Datasource\Sections\DefaultSection\Section::class,
        'title' => trans('datasource::sections.default.title'),
    ],
    'images'     => [
        'class' => KodiCMS\Datasource\Sections\Images\Section::class,
        'title' => trans('datasource::sections.images.title'),
        'icon'  => 'image',
    ],
];
