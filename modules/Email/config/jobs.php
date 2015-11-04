<?php

return [
    'email:queue' => [
        'label'  => trans('email::core.jobs.queue'),
        'action' => 'cms:email:queue-send',
    ],
    'email:clean' => [
        'label'  => trans('email::core.jobs.clean'),
        'action' => 'cms:email:queue-clean',
    ],
];
