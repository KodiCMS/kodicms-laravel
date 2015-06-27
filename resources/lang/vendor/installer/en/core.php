<?php

return [
	'tests' => [
		'pass' => 'success',
		'failed' => 'failure',
		'success' => [

		],
		'errors' => [
			'php_version' => 'KodiCMS requires PHP 5.4 or newer, this version is :version.'
		]
	],
	'button' => [
		'empty_database' 		=> 'Clear database',
		'install' 					=> 'Establish',
	],
	'title' => [
		'language' 					=> 'Language',
		'environment' 			=> 'Check environment',
		'database' 					=> 'Database',
		'site_information' 	=> 'Summary',
		'user_settings' 		=> 'User Preferences',
		'site_settings' 		=> 'Site Settings',
		'regional_settings' => 'Regional settings',
		'not_installed' 		=> 'The system is not installed',
	],
	'field' => [
		'current_language' 	=> 'Current language',
		'db_server' 				=> 'Server',
		'db_username'				=> 'DB Username',
		'db_password' 			=> 'DB Password',
		'db_database' 			=> 'Database name',
		'db_preffix' 				=> 'Table prefix',
		'username' 					=> 'username',
		'password' 					=> 'password',
		'password_conform' 	=> 'Confirm password',
		'email' 						=> 'E-mail',
		'site_title' 				=> 'Name of the site',
		'admin_dir_name' 		=> 'The path to the admin. interface',
		'url_suffix' 				=> 'URL suffix',
		'interface_locale' 	=> 'Language',
		'timezone' 					=> 'Time Zone',
		'date_format' 			=> 'Time Format',
	],
	'messages' => [
		'not_installed' 									=> 'File Not Found environment :file. You can create it manually and install the system via the console, or use the installer.',
		'database_name_inforamtion' 			=> 'You must specify the name of an existing database',
		'database_connection_failed'			=> 'Unable to connect to database',
		'database_no_password' 						=> 'If you connect to the database does not require a password, leave it blank.',
		'database_connection_information' => 'You need to specify the figures in the database connection. For details, contact the administrator.',
	]

];