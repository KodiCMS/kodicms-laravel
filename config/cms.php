<?php

return [
	'title' => 'KodiCMS',
	'backend_path' => env('ADMIN_DIR_NAME', 'backend'),

	'modules' => ['API', 'CMS', 'Plugins', 'Pages', 'Users', 'Email', 'Cron', 'Widgets', 'Filemanager', 'Installer', 'Dashboard'],

	'theme' => [
		'default' => 'default',
		'list' => [
			'default', 'asphalt', 'purple-hills', 'adminflare', 'dust', 'frost', 'fresh', 'silver', 'clean', 'white'
		]
	],
	'wysiwyg' => [
		'ace_themes' => [
			'ambiance' => 'ambiance',
			'chaos' => 'chaos',
			'chrome' => 'chrome',
			'clouds' => 'clouds',
			'clouds_midnight' => 'clouds_midnight',
			'cobalt' => 'cobalt',
			'crimson_editor' => 'crimson_editor',
			'dawn' => 'dawn',
			'dreamweaver' => 'dreamweaver',
			'eclipse' => 'eclipse',
			'github' => 'github',
			'idle_fingers' => 'idle_fingers',
			'katzenmilch' => 'katzenmilch',
			'kr_theme' => 'kr_theme',
			'kuroir' => 'kuroir',
			'merbivore' => 'merbivore',
			'merbivore_soft' => 'merbivore_soft',
			'mono_industrial' => 'mono_industrial',
			'monokai' => 'monokai',
			'pastel_on_dark' => 'pastel_on_dark',
			'solarized_dark' => 'solarized_dark',
			'solarized_light' => 'solarized_light',
			'terminal' => 'terminal',
			'textmate' => 'textmate',
			'tomorrow' => 'tomorrow',
			'tomorrow_night' => 'tomorrow_night',
			'tomorrow_night_blue' => 'tomorrow_night_blue',
			'tomorrow_night_bright' => 'tomorrow_night_bright',
			'tomorrow_night_eighties' => 'tomorrow_night_eighties',
			'twilight' => 'twilight',
			'vibrant_ink' => 'vibrant_ink',
			'xcode' => 'xcode'
		]
	],
	'default_html_editor' => 'ckeditor',
	'default_code_editor' => 'ace',
	'default_ace_theme' => 'textmate',
	'locales' => [
		//'en' => 'English',
		'ru' => 'Русский'
	],
	'date_format' => 'Y-m-d H:i:s',
	'date_format_list' => array(
		'Y-m-d H:i:s',
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