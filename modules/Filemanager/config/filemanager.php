<?php

return [
    'public' => [
        'driver'        => \KodiCMS\Filemanager\elFinder\VolumeLocalFileSystem::class,
        'path'          => public_path('assets'),
        'URL'           => url('assets'),
        'alias'         => 'public\assets',
        'uploadMaxSize' => '32M',
        'mimeDetect'    => 'internal',
        'imgLib'        => 'gd',
        'attributes'    => [
            [
                'pattern' => '/\.(tmb|quarantine|gitignore)/',
                'read'    => false,
                'write'   => false,
                'locked'  => true,
                'hidden'  => true,
            ],
        ],
    ],
];
