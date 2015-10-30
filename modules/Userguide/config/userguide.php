<?php

return [
    // Leave this alone
    'modules' => [
        'userguide' => [
            // Whether this modules userguide pages should be shown
            'enabled'     => true,
            // Index page
            'index_page'  => 'works',
            // The name that should show up on the userguide index page
            'name'        => 'Userguide',
            'description' => 'The userguide module provides documentation viewing including browsing the source code comments.',
        ],
        // This should be the path to this modules userguide pages, without the 'guide/'. Ex: '/guide/modulename/' would be 'modulename'
        'laravel'   => [
            // Whether this modules userguide pages should be shown
            'enabled'     => true,
            'index_page'  => 'structure',
            // The name that should show up on the userguide index page
            'name'        => 'Laravel',
            'description' => 'Documentation for Laravel framework.',
        ],
    ],
];
