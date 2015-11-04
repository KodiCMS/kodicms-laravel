<?php

return [
    [
        'name'     => 'System',
        'children' => [
            [
                'name'        => 'Users',
                'label'       => 'users::core.title.list',
                'url'         => route('backend.user.list'),
                'permissions' => 'users.index',
                'priority'    => 200,
                'icon'        => 'user',
            ],
            [
                'name'        => 'Roles',
                'label'       => 'users::role.title.list',
                'url'         => route('backend.role.list'),
                'permissions' => 'roles.index',
                'priority'    => 300,
                'icon'        => 'group',
            ],
        ],
    ],
];
