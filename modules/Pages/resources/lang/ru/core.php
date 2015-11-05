<?php

return [
    'title'    => [
        'pages'   => [
            'create' => 'Новая страница',
            'list'   => 'Страницы',
            'edit'   => 'Редактирование страницы :title',
        ],
        'layouts' => [
            'list' => 'Шаблоны',
        ],
    ],
    'status'   => [
        'none'      => 'Не указан',
        'hidden'    => 'Скрытая',
        'draft'     => 'Черновик',
        'published' => 'Опубликована',
        'pended'    => 'Ожидание',
    ],
    'button'   => [
        'add'        => 'Добавить',
        'reorder'    => 'Сортировать',
        'view_front' => 'Посмотреть',
        'search'     => 'Искать',
    ],
    'tab'      => [
        'page' => [
            'content' => 'Контент',
            'meta'    => 'Meta-информация',
            'options' => 'Настройки',
            'routes'  => 'Маршруты',
        ],
    ],
    'label'    => [
        'page' => [
            'created_by'     => 'Создал :anchor :date ',
            'updated_by'     => 'Обновил :anchor :date ',
            'layout_not_set' => 'Шаблон не указан',
            'current_layout' => 'Текущий шаблон :name',
            'redirect'       => 'Редирект: :url',
            'behavior'       => 'Поведение: :behavior',
        ],
    ],
    'field'    => [
        'title'            => 'Заголовок',
        'slug'             => 'Часть URL',
        'name'             => 'Заголовок',
        'page'             => 'Страница',
        'date'             => 'Дата',
        'status'           => 'Статус',
        'actions'          => 'Действия',
        'search'           => 'Поиск',
        'breadcrumb'       => 'Хлебные крошки',
        'meta_title'       => 'Meta-заголовок',
        'meta_keywords'    => 'Ключевые слова',
        'meta_description' => 'Описание',
        'robots'           => 'Индексация поисковиками',
        'is_redirect'      => 'Редирект',
        'redirect_url'     => 'URL адрес назначения',
        'parent_id'        => 'Родительская страница',
        'layout_file'      => 'Шаблон',
        'behavior'         => 'Поведение',
        'published_at'     => 'Опубликована',
        'created_by_id'    => 'Создал',
        'updated_by_id'    => 'Обновил',
    ],
    'messages' => [
        'not_found'          => 'Страница не найдена',
        'layout_not_set'     => 'Для текущей страницы не указан шаблон',
        'updated'            => 'Настройки страницы сохранены',
        'created'            => 'Страница создана',
        'behavior_no_routes' => 'Данный тип поведения страницы не имеет внутренних маршрутов',
        'deleted'            => 'Страница удалена',
    ],
];
