<?php

return [
	'title' => [
		'list' => 'Пользователи',
		'profile' => 'Профиль',
		'profile_alternate' => 'Профиль пользователя :name',
		'settings' => 'Настройки',
		'permissions' => 'Права доступа',
		'edit' => 'Редактирование пользователя :name'
	],
	'role' => [
		'sections' => [
			'list' => 'Роли',
		],
	],
	'field' => [
		'username' => 'Имя пользователя',
		'email' => 'E-mail',
		'last_login' => 'Последний вход',
		'roles' => 'Роли',
		'actions' => 'Действия',
		'auth' => [
			'username' => 'Логин или E-mail',
			'password' => 'Пароль',
			'email' => 'E-mail адрес',
			'forgot' => 'Забыли пароль?',
			'remember' => 'Запомнить меня на :lifetime дней'
		]
	],
	'button' => [
		'login' => 'Вход',
		'logout' => 'Выход',
		'send_password' => 'Выслать пароль',
		'edit' => 'Редактировать'
	],
	'messages' => [
		'user' => [
			'not_found'	=> 'Пользователь не найден',
			'deleted' => 'Пользователь удален',
		],
		'auth' => [
			'forgot' => 'Укажите email адрес, для которого вы хотите восстановить пароль.',
			'deny_access' => 'Доступ запрещен',
			'no_permissions' => 'У вас нет необходимых прав',
			'unauthorized' => 'Необходима авторизация'
		]
	]
];