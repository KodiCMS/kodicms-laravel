<?php

return [
    'page_menu'        => [
        'title'   => 'Меню',
        'label'   => [
            'excluded_pages' => 'Исключить страницы из списка',
            'linked_page'    => '-- Относительно текущей страницы --',
        ],
        'setting' => [
            'start_page'           => 'Корневая страница',
            'include_children'     => 'Выводить дочерние элементы',
            'include_hidden_pages' => 'Показывать скрытые страницы',
            'page_level'           => 'Задать уровень',
        ],
    ],
    'page_list'        => [
        'title'   => 'Список страниц',
        'label'   => [
            'linked_page' => '-- Относительно текущей страницы --',
        ],
        'setting' => [
            'start_page'          => 'Корневая страница',
            'include_user_object' => 'Загружать объект пользователя',
        ],
    ],
    'page_breadcrumbs' => [
        'title' => 'Хлебные крошки',
        'label' => [
            'excluded_pages' => 'Исключить страницы из списка',
        ],
    ],
];
