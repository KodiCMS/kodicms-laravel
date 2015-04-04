<?php

return [
	'title' => 'KodiCMS',
	'backend_path' => env('ADMIN_DIR_NAME', 'backend'),

	'modules' => ['API', 'CMS', 'Plugins', 'Pages', 'Users', 'Email', 'Cron', 'Widgets', 'Filemanager'],

	'theme' => [
		// TODO: разобраться с названием тем
		'default' => 'theme-default',
		'list' => [
			'default', 'asphalt', 'purple-hills', 'adminflare', 'dust', 'frost', 'fresh', 'silver', 'clean', 'white'
		]
	],
	'wysiwyg' => [
		'ace' => [
			'theme' => 'textmate'
		],
		'default_html_editor' => NULL,
		'default_code_editor' => NULL
	]
];