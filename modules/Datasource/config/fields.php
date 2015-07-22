<?php

return [
	'primary' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Primary::class,
		'title' => trans('datasource::fields.type.primary')
	],
	'boolean' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Boolean::class,
		'title' => trans('datasource::fields.types.boolean'),
		'edit_template' => 'datasource::field.types.boolean'
	],
	'string' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\String::class,
		'title' => trans('datasource::fields.types.string'),
		'edit_template' => 'datasource::field.types.string'
	],
	'timestamp' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Timestamp::class,
		'title' => trans('datasource::fields.types.timestamp')
	],
	'date' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Date::class,
		'title' => trans('datasource::fields.types.date'),
		'edit_template' => 'datasource::field.types.date'
	],
	'datetime' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\DateTime::class,
		'title' => trans('datasource::fields.types.datetime'),
		'edit_template' => 'datasource::field.types.date'
	]
];