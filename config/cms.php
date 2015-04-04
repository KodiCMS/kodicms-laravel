<?php

return [
	'backend_path' => env('ADMIN_DIR_NAME', 'backend'),

	'modules' => ['API', 'CMS', 'Plugins', 'Pages', 'Users', 'Email', 'Cron', 'Widgets', 'Filemanager'],

	'wysiwyg' => [
		'ace' => [
			'theme' => 'textmate'
		],
		'default_html_editor' => NULL,
		'default_code_editor' => NULL
	]
];