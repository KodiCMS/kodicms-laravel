<?php

return [
    'title'         => [
        'section'   => 'E-mail',
        'templates' => [
            'list'   => 'Письма',
            'create' => 'Новое письмо',
            'edit'   => 'Редактирование письма',
        ],
        'events'    => [
            'list'   => 'Почтовые события',
            'create' => 'Новое событие',
            'edit'   => 'Редактирование события :title',
        ],
    ],
    'button'        => [
        'events'    => [
            'create' => 'Создать событие',
        ],
        'templates' => [
            'create' => 'Создать письмо',
        ],
    ],
    'field'         => [
        'events'    => [
            'name'   => 'Название события',
            'code'   => 'Код события',
            'fields' => 'Параметры',
        ],
        'templates' => [
            'email_event'  => 'Почтовое событие',
            'status'       => 'Статус',
            'use_queue'    => 'Метод отправки сообщения',
            'email_from'   => 'От кого',
            'email_to'     => 'Кому',
            'subject'      => 'Тема',
            'message'      => 'Текст письма',
            'message_type' => 'Тип сообщения',
            'cc'           => 'Копия',
            'bcc'          => 'Скрытая копия',
            'reply_to'     => 'Ответ на',
        ],
        'actions'   => 'События',
    ],
    'messages'      => [
        'events'    => [
            'created'       => 'Событие создано',
            'updated'       => 'Событие обновлено',
            'deleted'       => 'Событие удалено',
            'not_found'     => 'Событие не найдено',
            'job_not_found' => 'Событие :name не найдено',
        ],
        'templates' => [
            'created'   => 'Шаблон создан',
            'updated'   => 'Шаблон обновлен',
            'deleted'   => 'Шаблон удален',
            'not_found' => 'Шаблон не найден',
        ],
    ],
    'tab'           => [
        'general'      => 'Общая информация',
        'fields'       => 'Используемые параметры',
        'message'      => 'Текст письма',
        'message_info' => 'Коллекция шаблонов писем с отзывчивым дизайном :link',
    ],
    'templates'     => [
        'title'      => 'Связанные почтовые шаблоны',
        'created_at' => 'Время запуска',
        'status'     => 'Статус выполнения',
    ],
    'statuses'      => [
        0 => 'Неактивен',
        1 => 'Активен',
    ],
    'queue'         => [
        0 => 'Прямая отправка',
        1 => 'Постановка в очередь',
    ],
    'message_types' => [
        'html' => 'HTML',
        'text' => 'Простой текст',
    ],
    'template_data' => [
        'default_email'    => 'E-Mail адрес по умолчанию',
        'site_title'       => 'Заголовок сайта',
        'site_description' => 'Описание сайта',
        'base_url'         => 'Адрес сайта (в формате :format)',
        'current_date'     => 'Текущая дата (в формате :format)',
        'current_time'     => 'Текущее время (в формате :format)',
    ],
    'settings'      => [
        'title'         => 'Настройки почты',
        'queue'         => [
            'title'             => 'Параметры очереди сообщений',
            'batch_size'        => 'Кол-во сообщений отправляемых за одну отправку',
            'batch_help'        => 'The number of emails to send out in each batch. This should be tuned to your servers abilities and the frequency of the cron.',
            'interval'          => 'Интервал между отправкой',
            'max_attempts'      => 'Максимальное кол-во попыток отправки',
            'max_attempts_help' => 'The maximum number of attempts to send an email before giving up. An email may fail to send if the server is too busy, or there`s a problem with the email itself.',
        ],
        'default_email' => 'Email адрес по умолчанию',
        'email_driver'  => 'Драйвер',
        'test'          => [
            'label'           => 'Для отправки тестового письма необходимо сохранить настройки',
            'btn'             => 'Отправить тестовое письмо',
            'subject'         => 'Тестовое письмо',
            'message'         => 'Тестовое сообщение',
            'result_positive' => 'Тестовое письмо было успешно отправлено',
            'result_negative' => 'Тестовое письмо не было отправлено',
        ],
        'sendmail'      => [
            'path'        => 'Путь к исполняемому файлу',
            'placeholder' => 'Например: /usr/sbin/sendmail',
            'help'        => 'Путь до программы sendmail, обычно :path1 или :path2. :link',
        ],
        'smtp'          => [
            'host'       => 'Сервер',
            'port'       => 'Порт',
            'username'   => 'Имя пользователя',
            'password'   => 'Пароль',
            'encryption' => 'Шифрование',
        ],
        'mailgun'       => [
            'domain' => 'Домен',
            'secret' => 'Секретный ключ',
        ],
        'mandrill'      => [
            'secret' => 'Секретный ключ',
        ],
    ],
    'jobs'          => [
        'queue' => 'Отправка отложенных писем',
        'clean' => 'Удаление старых сообщений из очереди',
    ],
];
