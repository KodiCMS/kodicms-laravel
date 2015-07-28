<?php

return [
	'primary' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Primary::class,
		'title' => trans('datasource::fields.primary.title')
	],
	'integer' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Integer::class,
		'title' => trans('datasource::fields.integer.title'),
		'edit_template' => 'datasource::field.types.integer'
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
	'slug' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Slug::class,
		'title' => trans('datasource::fields.slug.title'),
		'edit_template' => 'datasource::field.types.slug'
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
	'boolean' => [
		'class' => KodiCMS\Datasource\Fields\Primitive\Boolean::class,
		'title' => trans('datasource::fields.boolean.title'),
		'edit_template' => 'datasource::field.types.boolean'
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
	],

	'has_one' => [
		'class' => KodiCMS\Datasource\Fields\Relation\HasOne::class,
		'title' => trans('datasource::fields.has_one.title'),
		'edit_template' => 'datasource::field.types.has_one',
		'category' => 'Relations'
	],
	'has_many' => [
		'class' => KodiCMS\Datasource\Fields\Relation\HasMany::class,
		'title' => trans('datasource::fields.has_many.title'),
		'edit_template' => 'datasource::field.types.has_many',
		'category' => 'Relations'
	],
	'belongs_to' => [
		'class' => KodiCMS\Datasource\Fields\Relation\BelongsTo::class,
		'title' => trans('datasource::fields.belongs_to.title'),
		'category' => 'Relations'
	],
];