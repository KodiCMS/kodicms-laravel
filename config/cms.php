<?php

return [
	'title' => 'KodiCMS',
	'backend_path' => env('ADMIN_DIR_NAME', 'backend'),

	'modules' => ['API', 'CMS', 'Plugins', 'Pages', 'Users', 'Email', 'Cron', 'Widgets', 'Filemanager', 'Installer'],

	'theme' => [
		// TODO: разобраться с названием тем
		'default' => 'default',
		'list' => [
			'default', 'asphalt', 'purple-hills', 'adminflare', 'dust', 'frost', 'fresh', 'silver', 'clean', 'white'
		]
	],
	'wysiwyg' => [
		'ace' => [
			'theme' => 'textmate'
		]
	],
	'default_html_editor' => 'ckeditor',
	'default_code_editor' => 'ace',
	'locales' => [
		'en' => 'English',
		'ru' => 'Russian'
	],
	'date_format_list' => array(
		'Y-m-d',
		'd.m.Y',
		'Y/m/d',
		'm/d/Y',
		'd/m/Y',
		'd M.',
		'd M. Y',
		'd F',
		'd F Y',
		'd F Y H:i',
		'l, j-S F Y'
	),
];