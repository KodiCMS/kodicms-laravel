<?php

return [
    // Primitive
    'primary'      => [
        'class' => KodiCMS\Datasource\Fields\Primitive\Primary::class,
        'title' => trans('datasource::fields.primary.title'),
    ],
    'integer'      => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\Integer::class,
        'title'         => trans('datasource::fields.integer.title'),
        'edit_template' => 'datasource::field.types.integer',
    ],
    'string'       => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\String::class,
        'title'         => trans('datasource::fields.string.title'),
        'edit_template' => 'datasource::field.types.string',
    ],
    'email'        => [
        'class' => KodiCMS\Datasource\Fields\Primitive\Email::class,
        'title' => trans('datasource::fields.email.title'),
    ],
    'slug'         => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\Slug::class,
        'title'         => trans('datasource::fields.slug.title'),
        'edit_template' => 'datasource::field.types.slug',
    ],
    'textarea'     => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\Textarea::class,
        'title'         => trans('datasource::fields.textarea.title'),
        'edit_template' => 'datasource::field.types.textarea',
    ],
    'html'         => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\HTML::class,
        'title'         => trans('datasource::fields.html.title'),
        'edit_template' => 'datasource::field.types.html',
    ],
    'boolean'      => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\Boolean::class,
        'title'         => trans('datasource::fields.boolean.title'),
        'edit_template' => 'datasource::field.types.boolean',
    ],
    'timestamp'    => [
        'class' => KodiCMS\Datasource\Fields\Primitive\Timestamp::class,
        'title' => trans('datasource::fields.timestamp.title'),
    ],
    'date'         => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\Date::class,
        'title'         => trans('datasource::fields.date.title'),
        'edit_template' => 'datasource::field.types.date',
    ],
    'datetime'     => [
        'class'         => KodiCMS\Datasource\Fields\Primitive\DateTime::class,
        'title'         => trans('datasource::fields.datetime.title'),
        'edit_template' => 'datasource::field.types.date',
    ],
    /*'select' => [
        'class' => KodiCMS\Datasource\Fields\Primitive\Select::class,
        'title' => trans('datasource::fields.select.title'),
        'edit_template' => 'datasource::field.types.select'
    ],*/

    // File
    'file'         => [
        'class'         => KodiCMS\Datasource\Fields\File::class,
        'title'         => trans('datasource::fields.file.title'),
        'edit_template' => 'datasource::field.types.file',
        'category'      => 'File',
    ],
    'image'        => [
        'class'             => KodiCMS\Datasource\Fields\File\Image::class,
        'title'             => trans('datasource::fields.image.title'),
        'edit_template'     => 'datasource::field.types.image',
        'document_template' => 'datasource::document.field.file',
        'category'          => 'File',
    ],
    // Source
    'user'         => [
        'class'         => KodiCMS\Datasource\Fields\Source\User::class,
        'title'         => trans('datasource::fields.user.title'),
        'edit_template' => 'datasource::field.types.user',
        'category'      => 'Source',
    ],
    'images'       => [
        'class'         => KodiCMS\Datasource\Fields\Source\Images::class,
        'title'         => trans('datasource::fields.images.title'),
        'edit_template' => 'datasource::field.types.images',
        'category'      => 'Source',
    ],
    // Relations
    'has_one'      => [
        'class'         => KodiCMS\Datasource\Fields\Relation\HasOne::class,
        'title'         => trans('datasource::fields.has_one.title'),
        'edit_template' => 'datasource::field.types.has_one',
        'category'      => 'Relations',
    ],
    'has_many'     => [
        'class'         => KodiCMS\Datasource\Fields\Relation\HasMany::class,
        'title'         => trans('datasource::fields.has_many.title'),
        'edit_template' => 'datasource::field.types.has_many',
        'category'      => 'Relations',
    ],
    'many_to_many' => [
        'class'         => KodiCMS\Datasource\Fields\Relation\ManyToMany::class,
        'title'         => trans('datasource::fields.many_to_many.title'),
        'edit_template' => 'datasource::field.types.many_to_many',
        'category'      => 'Relations',
    ],
    'belongs_to'   => [
        'class'    => KodiCMS\Datasource\Fields\Relation\BelongsTo::class,
        'title'    => trans('datasource::fields.belongs_to.title'),
        'category' => 'Relations',
    ],
];
