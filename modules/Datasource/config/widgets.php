<?php

return [
	'Datasource' => [
		'datasource.list' => [
			'class' => \KodiCMS\Datasource\Widget\DatasourceList::class,
			'title' => trans('datasource::widgets.list.title')
		],
		'datasource.document' => [
			'class' => \KodiCMS\Datasource\Widget\DatasourceDocument::class,
			'title' => trans('datasource::widgets.document.title')
		],
		'datasource.document_creator' => [
			'class' => \KodiCMS\Datasource\Widget\DatasourceDocumentCreator::class,
			'title' => trans('datasource::widgets.document_creator.title')
		]
	]
];