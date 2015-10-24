<?php

return [
	'title' => [
		'list' => 'Users',
		'profile' => 'Profile',
		'profile_alternate' => 'Profile :name',
		'settings' => 'Settings',
		'permissions' => 'Permissions',
		'edit' => 'Edit :name',
		'create' => 'Create user',
		'theme' => 'Theme',
	],
	'tab' => [
		'general' => 'Main',
		'password' => 'Password',
		'roles' => 'Roles',
		'theme' => 'Theme',
	],
	'field' => [
		'username' => 'Username',
		'email' => 'E-mail',
		'password' => 'Password',
		'password_confirm' => 'Confirm password',
		'last_login' => 'Last login',
		'locale' => 'Language',
		'default_locale' => 'System default (:locale)',
		'roles' => 'Roles',
		'actions' => 'Actions',
		'auth' => [
			'username' => 'Username or E-mail',
			'password' => 'Password',
			'email' => 'E-mail address',
			'forgot' => 'Forgot your password?',
			'remember' => 'Remember me on :lifetime days',
		]
	],
	'rule' => [
		'username' => 'No less :num characters. It must be unique.',
		'password_change' => 'If you do not want to change your password - leave the field empty.',
		'roles' => 'Roles define the rights of users to enable/disable control panel screens.',
	],
	'button' => [
		'login' => 'Login',
		'logout' => 'Logout',
		'send_password' => 'Send password',
		'edit' => 'Edit',
		'create' => 'Add User',
	],
	'messages' => [
		'user' => [
			'not_found' => 'User not found',
			'deleted' => 'User removed',
			'updated' => 'User updated',
			'created' => 'User created',
			'empty' => 'Section is empty',
		],
		'auth' => [
			'forgot' => 'Enter the email address to which you want to recover your password.',
			'deny_access' => 'Access denied',
			'no_permissions' => 'You do not have the required permissions',
			'unauthorized' => 'For clients',
			'user_not_found' => 'Wrong login or password',
		]
	]
];