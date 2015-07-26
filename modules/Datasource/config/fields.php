<?php

return [
	'primary' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Primary::class,
		'title' => trans('datasource::fields.primary.title')
	],
	'boolean' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Boolean::class,
		'title' => trans('datasource::fields.boolean.title'),
		'edit_template' => 'datasource::field.types.boolean'
	],
	'string' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\String::class,
		'title' => trans('datasource::fields.string.title'),
		'edit_template' => 'datasource::field.types.string'
	],
	'email' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Email::class,
		'title' => trans('datasource::fields.email.title')
	],
	'textarea' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Textarea::class,
		'title' => trans('datasource::fields.textarea.title'),
		'edit_template' => 'datasource::field.types.textarea'
	],
	'html' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\HTML::class,
		'title' => trans('datasource::fields.html.title'),
		'edit_template' => 'datasource::field.types.html'
	],
	'timestamp' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Timestamp::class,
		'title' => trans('datasource::fields.timestamp.title')
	],
	'date' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Date::class,
		'title' => trans('datasource::fields.date.title'),
		'edit_template' => 'datasource::field.types.date'
	],
	'datetime' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\DateTime::class,
		'title' => trans('datasource::fields.datetime.title'),
		'edit_template' => 'datasource::field.types.date'
	]
];