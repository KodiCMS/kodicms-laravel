<?php

return [
    'tests'    => [
        'pass'     => 'Успешно',
        'failed'   => 'Провален',
        'success'  => [

        ],
        'errors'   => [
            'php_version' => 'KodiCMS requires PHP 5.4 or newer, this version is :version.',
        ],
        'messages' => [
            'pass' => 'Пройдено',
        ],
    ],
    'button'   => [
        'empty_database' => 'Очистить БД',
        'install'        => 'Установить',
    ],
    'title'    => [
        'language'             => 'Язык',
        'environment'          => 'Проверка окружения',
        'environment_optional' => 'Опционально',
        'database'             => 'База данных',
        'site_information'     => 'Основная информация',
        'user_settings'        => 'Настройки пользователя',
        'site_settings'        => 'Настройки сайта',
        'regional_settings'    => 'Региональные настройки',
        'not_installed'        => 'Система не установлена',
        'other'                => 'Другое',
    ],
    'field'    => [
        'current_language' => 'Текущий язык',
        'db_driver'        => 'Драйвер',
        'db_server'        => 'Сервер',
        'db_username'      => 'Имя пользователя',
        'db_password'      => 'Пароль',
        'db_database'      => 'Имя базы данных',
        'db_preffix'       => 'Префикс таблиц',
        'username'         => 'Имя пользователя',
        'password'         => 'Пароль',
        'password_conform' => 'Подтверждения пароля',
        'email'            => 'E-mail',
        'site_title'       => 'Название сайта',
        'admin_dir_name'   => 'Путь до админ. интерфейса',
        'url_suffix'       => 'URL suffix',
        'interface_locale' => 'Язык интерфейса',
        'timezone'         => 'Временная зона',
        'date_format'      => 'Формат времени',
        'cache_type'       => 'Драйвер кеша',
        'session_type'     => 'Драйвер сессий',
    ],
    'messages' => [
        'not_installed'                   => 'Не найден файл окружения :file. Вы можете создать его вручную и установить систему через консоль, либо воспользоваться инсталлятором.',
        'database_name_inforamtion'       => 'Необходимо указать название существующей базы данных. или указать имя файла (при использовании sqlite)',
        'database_connection_failed'      => 'Не удалось подключиться к БД',
        'database_no_password'            => 'Если для подключения к БД не требуется пароль, оставьте поле пустым.',
        'database_connection_information' => 'Вам необходимо указать даные подключения к базе данных. Для подробностей обратитесь к администратору.',
        'environment_optional'            => 'Рекомендуемые требования для корректной работы компонентов системы',
        'environment_failed'              => 'Необходимо исправить проблемы',
        'environment_passed'              => 'Ваша система удовлетворяет всем требованиям KodiCMS',
    ],
    'wizard'   => [
        'finish'   => 'Завершить',
        'next'     => 'Далее',
        'previous' => 'Назад',
        'loading'  => 'Загрузка',
        'messages' => [
            'next_step_error' => 'Для перехода на следующий этап вы должны исправить все ошибки.',
        ],
    ],
];
