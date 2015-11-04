<?php

return [
    'default_template_data' => [
        'default_email'    => trans('email::core.template_data.default_email'),
        'site_title'       => trans('email::core.template_data.site_title'),
        'site_description' => trans('email::core.template_data.site_description'),
        'base_url'         => trans('email::core.template_data.base_url', ['format' => url('/')]),
        'current_date'     => trans('email::core.template_data.current_date', ['format' => config('cms.date_format')]),
        'current_time'     => trans('email::core.template_data.current_time', ['format' => 'H:i:s']),
    ],
];
