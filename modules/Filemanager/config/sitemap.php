<?php

return [
    [
        'name'        => 'File manager',
        'label'       => 'filemanager::core.title.index',
        'url'         => route('backend.filemanager'),
        'priority'    => 6000,
        'permissions' => 'filemanager.index',
        'icon'        => 'folder-open',
    ],
];
