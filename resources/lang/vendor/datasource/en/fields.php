<?php

return [
	'settings' => [
		'hint' => 'Hint'
	],
	'primary' => [
		'title' => 'Primary'
	],
	'integer' => [
		'title' => 'Number',
		'length' => 'Length',
		'min' => 'Minimum value',
		'max' => 'Maximum value',
		'auto_increment' => 'Auto increment',
		'increment_step' => 'Step'
	],
	'string' => [
		'title' => 'Line',
		'use_filemanager' => 'Use file manager',
		'length' => 'Length'
	],
	'email' => [
		'title' => 'Email'
	],
	'slug' => [
		'title' => 'Slug',
		'is_unique' => 'Unique',
		'from_document_title' => 'Take the value from the document header',
		'separator' => 'Separator',
		'must_be_unique' => 'The value of the field must be unique',
	],
	'textarea' => [
		'title' => 'Теxt',
		'allow_html' => 'Allow HTML tags',
		'filter_html' => 'Filter HTML tag',
		'allowed_tags' => 'Allowed tags',
		'num_rows' => 'num_rows'
	],
	'html' => [
		'title' => 'HTML editor',
		'filter_html' => 'Filter HTML tag',
		'allowed_tags' => 'Allowed tags',
		'wysiwyg' => 'Text editor',
		'remove_empty_tags' => 'Remove empty tags'
	],
	'boolean' => [
		'title' => 'Boolean',
		'style' => 'Style',
		'true'  => 'True',
		'false' => 'False',
	],
	'timestamp' => [
		'title' => 'Timestamp'
	],
	'date' => [
		'title' => 'Date',
		'set_current_date' => 'Set current date'
	],
	'datetime' => [
		'title' => 'Date/Time'
	],
	'has_one' => [
		'title' => 'HasOne'
	],
	'belongs_to' => [
		'title' => 'Belongs to'
	]
];
