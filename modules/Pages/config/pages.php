<?php

use \KodiCMS\Pages\Model\FrontendPage;

return [
    'check_date'     => false,
    'default_status' => FrontendPage::STATUS_PUBLISHED,
    'cache'          => [
        'findByField' => \Carbon\Carbon::now()->addMinutes(10),
    ],
    'similar'        => [
        'similarity'         => 3, // Степень схожести слова (Чем меньше число, тем меньше точность)
        'return_parent_page' => false, // Включить переход на уровень выше, если слово не найдено
        'find_in_statuses'   => [ // Статусы страниц, в которых искать
            FrontendPage::STATUS_PUBLISHED,
        ],
    ],
];
