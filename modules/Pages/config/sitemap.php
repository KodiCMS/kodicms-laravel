<?php

return [
	[
		'name' => 'Pages',
		'translate' => 'pages::page.sections.list',
		'url' => route('backend.page.list'),
		'permissions' => 'page.index',
		'priority' => 100,
		'icon' => 'sitemap'
	],
	[
		'name' => 'Design',
		'children' => [
			[
				'name' => 'Layouts',
				'url' => route('backend.layout.list'),
				'permissions' => 'layout.index',
				'priority' => 100,
				'icon' => 'desktop'
			]
		]
	],
];
