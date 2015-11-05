<?php

return [
    'Page' => [
        'page.menu'        => [
            'class' => '\KodiCMS\Pages\Widget\PageMenu',
            'title' => trans('pages::widgets.page_menu.title'),
        ],
        'page.list'        => [
            'class' => '\KodiCMS\Pages\Widget\PageList',
            'title' => trans('pages::widgets.page_list.title'),
        ],
        'page.breadcrumbs' => [
            'class' => '\KodiCMS\Pages\Widget\PageBreadcrumbs',
            'title' => trans('pages::widgets.page_breadcrumbs.title'),
        ],
    ],
];
