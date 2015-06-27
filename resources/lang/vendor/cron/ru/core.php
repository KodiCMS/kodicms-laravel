<?php
return [
	'title'    => [
		'list' => 'Задачи',
		'cron' => [
			'create' => 'Новая задача',
			'edit'   => 'Редактирование задачи :title',
		]
	],
	'field'    => [
		'name'       => 'Название',
		'task_name'  => 'Функция',
		'date_start' => 'Первый запуск',
		'date_end'   => 'Последний запуск',
		'last_run'   => 'Последний запуск',
		'next_run'   => 'Следующий запуск',
		'interval'   => 'Интервал',
		'crontime'   => 'Строка crontime',
		'status'     => 'Статус',
		'actions'    => 'Действия',
		'attempts'   => 'Попыток',
	],
	'button'   => [
		'create' => 'Создать задачу',
		'run'    => 'Запустить задачу',
	],
	'tab'      => [
		'general' => 'Общая информация',
		'options' => 'Опции запуска',
	],
	'jobs'     => [
		'test' => 'Тестирование cron',
	],
	'interval' => [
		'minute' => 'Минута',
		'hour'   => 'Час',
		'day'    => 'День',
		'week'   => 'Неделя',
		'month'  => 'Месяц',
		'year'   => 'Год',
		'or'     => 'Или',
	],
	'crontab'  => [
		'help'    => 'Описание',
		'weekday' => 'День недели (0 - 7) (Воскресенье 0 или 7)',
		'month'   => 'Месяц (1 - 12)',
		'day'     => 'День (1 - 31)',
		'hour'    => 'Час (0 - 23)',
		'minute'  => 'Минута (0 - 59)',
	],
	'messages' => [
		'created'   => 'Задача создана',
		'updated'   => 'Задача обновлена',
		'deleted'   => 'Задача удалена',
		'runned'    => 'Задача запущена',
		'not_found' => 'Задача не найдена',
		'empty' => 'Нет созданных задач',
	],
	'statuses' => [
		-1 => 'Не выполнена',
		1  => 'Новая задача',
		2  => 'Выполняется сейчас',
		3  => 'Задача выполнена',
	],
	'logs'     => [
		'title'      => 'История выполнения',
		'created_at' => 'Время запуска',
		'status'     => 'Статус выполнения',
	],
	'settings' => [
		'title'  => 'Настройка задач',
		'info'   => 'При использовании cron необходимо в crontab добавить следующую строку:',
		'agents' => [
			'system' => 'Система',
			'cron'   => 'Crontab',
		],
	],
];