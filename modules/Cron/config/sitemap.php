<?php

return [
    [
        'name'     => 'System',
        'children' => [
            [
                'name'        => 'Cron jobs',
                'label'       => 'cron::core.title.list',
                'icon'        => 'bolt',
                'url'         => route('backend.cron.list'),
                'permissions' => 'cron.index',
                'priority'    => 500,
            ],
        ],
    ],
];
