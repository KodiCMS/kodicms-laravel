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
		'auto_increment' => 'Autoincrement',
		'increment_step' => 'Step'
	],
	'string' => [
		'title' => 'Line',
		'use_filemanager' => 'Use File Manager',
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
		'filter_html' => 'Filter HTML tags',
		'allowed_tags' => 'Allowed tags',
		'num_rows' => 'Number of rows'
	],
	'html' => [
		'title' => 'HTML editor',
		'filter_html' => 'Filter HTML tags',
		'allowed_tags' => 'Allowed tags',
		'wysiwyg' => 'Text editor',
		'remove_empty_tags' => 'Remove empty tags'
	],
	'boolean' => [
		'title' => 'Boolean',
		'style' => 'Style'
	],
	'timestamp' => [
		'title' => 'Timestamp'
	],
	'date' => [
		'title' => 'Date',
		'set_current_date' => 'Set current time'
	],
	'datetime' => [
		'title' => 'Date/Time'
	],
	'user' => [
		'title' => 'User',
		'current_only' => 'Current user only',
		'set_current' => 'When you create  select current user',
		'unique' => 'Unique users'
	],
	'images' => [
		'title' => 'Images',
		'upload_new' => 'Upload a new image',
		'remove_file' => 'Remove'
	],
	'file' => [
		'title' => 'File',
		'upload_new' => 'Upload new file',
		'allowed_types' => 'Allowed type files',
		'allowed_types_list' => 'Allowed type files [:types]',
		'max_file_size' => 'Maximum file size',
		'max_size' => 'Maximum size [:size]',
		'view_file' => 'View',
		'remove_file' => 'Remove'
	],
	'image' => [
		'title' => 'Image',
		'max_file_size' => 'Maximum file size',
		'settings' => 'Settings',
		'size' => 'Size',
		'quality' => 'Quality',
		'crop' => 'Crop',
		'aspect_ratio' => 'Aspect ratio'
	],
	'has_one' => [
		'title' => 'HasOne',
		'view_document' => 'View',
		'create_document' => 'Add',
		'datasource' => 'Section',
		'relation_type' => 'Relation type',
		'one_to_one' => 'One to one',
		'one_to_many' => 'One to many'
	],
	'has_many' => [
		'title' => 'HasMany',
		'datasource' => 'Section',
	],
	'many_to_many' => [
		'title' => 'ManyToMany',
		'datasource' => 'Section',
	],
	'belongs_to' => [
		'title' => 'Belongs to'
	]
];