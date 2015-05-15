<?php

use \KodiCMS\Pages\Model\FrontendPage;

return [
	'check_date' => false,

	'cache' => [
		'findByField' => \Carbon\Carbon::now()->addMinutes(10)
	],
	'similar' => [
		'similarity' => 3, // Степень схожести слова (Чем меньше число, тем меньше точность)
		'return_parent_page' => FALSE, // Включить переход на уровень выше, если слово не найдено
		'find_in_statuses' => [ // Статусы страниц, в которых искать
			FrontendPage::STATUS_PUBLISHED
		]
	]
];