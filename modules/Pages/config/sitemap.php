<?php

return [
    [
        'name'        => 'Pages',
        'label'       => 'pages::core.title.pages.list',
        'url'         => route('backend.page.list'),
        'permissions' => 'page.index',
        'priority'    => 100,
        'icon'        => 'sitemap',
    ],
    [
        'name'     => 'Design',
        'children' => [
            [
                'name'        => 'Layouts',
                'label'       => 'pages::core.title.layouts.list',
                'url'         => route('backend.layout.list'),
                'permissions' => 'layout.index',
                'priority'    => 100,
                'icon'        => 'desktop',
            ],
        ],
    ],
];
