<?php

return [
	'default' => [
		'class' => KodiCMS\Datasource\Sections\DefaultSection\Section::class,
		'title' => trans('datasource::sections.default.title')
	],
	'article' => [
		'class' => KodiCMS\Datasource\Sections\Article\Section::class,
		'title' => trans('datasource::sections.article.title')
	]
];