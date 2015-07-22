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
	]
];