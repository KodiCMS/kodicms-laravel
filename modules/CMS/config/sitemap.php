<?php

return [
    [
        'name'     => 'Content',
        'label'    => 'cms::core.title.content',
        'icon'     => 'pencil-square-o',
        'priority' => 200,
    ],
    [
        'name'     => 'Design',
        'label'    => 'cms::core.title.design',
        'icon'     => 'desktop',
        'priority' => 7000,
    ],
    [
        'name'     => 'System',
        'label'    => 'cms::core.title.system',
        'icon'     => 'cog',
        'priority' => 8000,
        'children' => [
            [
                'name'        => 'Information',
                'label'       => 'cms::core.title.about',
                'url'         => route('backend.about'),
                'permissions' => 'backend.about',
                'priority'    => 90,
                'icon'        => 'info-circle',
            ],
            [
                'name'        => 'Settings',
                'label'       => 'cms::core.title.settings',
                'url'         => route('backend.settings'),
                'permissions' => 'backend.settings',
                'priority'    => 100,
                'icon'        => 'cog',
            ],
            [
                'name'        => 'Update',
                'label'       => 'cms::core.title.update',
                'url'         => route('backend.update'),
                'permissions' => 'backend.update',
                'priority'    => 500,
                'icon'        => 'cloud-download',
            ],
        ],
    ],
];
