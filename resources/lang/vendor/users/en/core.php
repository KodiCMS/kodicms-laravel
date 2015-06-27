<?php

return [
	'title' => [
		'list' 							=> 'Users',
		'profile' 					=> 'Profile',
		'profile_alternate' => 'Profile :name',
		'settings' 					=> 'Settings',
		'permissions' 			=> 'Permissions',
		'edit' 							=> 'Edit :name',
		'create' 						=> 'Create User',
		'theme' 						=> 'theme',
	],
	'tab' => [
		'general' 	=> 'main',
		'password' 	=> 'password',
		'roles' 		=> 'role',
		'theme' 		=> 'theme',
	],
	'field' => [
		'username' 					=> 'Username',
		'email' 						=> 'E-mail',
		'password' 					=> 'Password',
		'password_confirm'	=> 'Confirm password',
		'last_login' 				=> 'Last sign in',
		'locale' 						=> 'Language system',
		'default_locale' 		=> 'Language System Default (:locale)',
		'roles' 						=> 'Role',
		'actions' 					=> 'Actions',
		'auth' => [
			'username' 	=> 'Username or E-mail',
			'password' 	=> 'Password',
			'email' 		=> 'E-mail address',
			'forgot' 		=> 'Forgot your password?',
			'remember' 	=> 'Remember me on :lifetime days',
		]
	],
	'rule' => [
		'username' 				=> 'No less :num characters. It must be unique.',
		'password_change' => 'If you do not want to change your password - leave the field empty.',
		'roles' 					=> 'Roles define the rights of users to enable/disable control panel screens.',
	],
	'button' => [
		'login' 				=> 'Login',
		'logout' 				=> 'Logout',
		'send_password' => 'Send password',
		'edit' 					=> 'Edit',
		'create'	 			=> 'Add User',
	],
	'messages' => [
		'user' => [
			'not_found'	=> 'User not found',
			'deleted' 	=> 'User removed',
			'updated' 	=> 'User updated',
			'created' 	=> 'User created',
			'empty' 		=> 'Under no documents',
		],
		'auth' => [
			'forgot' 					=> 'Enter the email address to which you want to recover your password.',
			'deny_access' 		=> 'access denied',
			'no_permissions' 	=> 'You do not have the required permissions',
			'unauthorized' 		=> 'For clients',
			'user_not_found' 	=> 'Wrong login or password',
		]
	]
];