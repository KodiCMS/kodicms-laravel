<?php

return [
	'default' => [
		'class' => KodiCMS\Datasource\Sections\DefaultSection\Section::class,
		'title' => trans('datasource::sections.default.title')
	],
	'article' => [
		'class' => KodiCMS\Datasource\Sections\Article\Section::class,
		'title' => trans('datasource::sections.article.title')
	],
	'images' => [
		'class' => KodiCMS\Datasource\Sections\Images\Section::class,
		'title' => trans('datasource::sections.images.title')
	]
];