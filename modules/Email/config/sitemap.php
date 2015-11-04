<?php

return [
    [
        'name'     => 'System',
        'children' => [
            [
                'name'     => 'Email',
                'label'    => 'email::core.title.section',
                'icon'     => 'envelope',
                'priority' => 400,
                'children' => [
                    [
                        'name'        => 'Email templates',
                        'label'       => 'email::core.title.templates.list',
                        'url'         => route('backend.email.template.list'),
                        'permissions' => 'email.template.list',
                        'icon'        => 'envelope-o',
                    ],
                    [
                        'name'        => 'Email types',
                        'label'       => 'email::core.title.events.list',
                        'url'         => route('backend.email.event.list'),
                        'permissions' => 'email.event.list',
                        'icon'        => 'exchange',
                    ],
                ],
            ],
        ],
    ],
];
