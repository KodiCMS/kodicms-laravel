<?php

return [
	'sections' => [
		'list' => 'Пользователи',
		'profile' => 'Профиль',
		'settings' => 'Настройки'
	],
	'role' => [
		'sections' => [
			'list' => 'Роли',
		],
	],
	'field' => [
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
		'send_password' => 'Выслать пароль'
	],
	'messages' => [
		'auth' => [
			'forgot' => 'Укажите email адрес, для которого вы хотите восстановить пароль.',
			'deny_access' => 'Доступ запрещен',
			'no_permissions' => 'У вас нет необходимых прав',
			'unauthorized' => 'Необходима авторизация'
		]
	]
];