<?php

return [
	'settings' => [
		'hint' => 'Подсказка'
	],
	'primary' => [
		'title' => 'Primary'
	],
	'integer' => [
		'title' => 'Число',
		'length' => 'Длина',
		'min' => 'Минимальное значение',
		'max' => 'Максимальное значение',
		'auto_increment' => 'Автоматическое приращение',
		'increment_step' => 'Шаг'
	],
	'string' => [
		'title' => 'Строка',
		'use_filemanager' => 'Использовать файловый менеджер',
		'length' => 'Длина'
	],
	'email' => [
		'title' => 'Email'
	],
	'slug' => [
		'title' => 'Slug',
		'is_unique' => 'Уникальное',
		'from_document_title' => 'Брать значение из заголовка документа',
		'separator' => 'Разделитель',
		'must_be_unique' => 'Значение поля должно быть уникальным',
	],
	'textarea' => [
		'title' => 'Текст',
		'allow_html' => 'Разрешить HTML теги',
		'filter_html' => 'Фильтровать HTML теги',
		'allowed_tags' => 'Разрешенные теги',
		'num_rows' => 'Кол-во строк'
	],
	'html' => [
		'title' => 'HTML c редактором',
		'filter_html' => 'Фильтровать HTML теги',
		'allowed_tags' => 'Разрешенные теги',
		'wysiwyg' => 'Редактор текста',
		'remove_empty_tags' => 'Удалять пустые теги'
	],
	'boolean' => [
		'title' => 'Boolean',
		'style' => 'Стиль'
	],
	'timestamp' => [
		'title' => 'Timestamp'
	],
	'date' => [
		'title' => 'Дата',
		'set_current_date' => 'Установить текущее время'
	],
	'datetime' => [
		'title' => 'Дата/Время'
	],
	'has_one' => [
		'title' => 'HasOne'
	],
	'belongs_to' => [
		'title' => 'Относится к'
	]
];