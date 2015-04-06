<?php

return [
	[
		'name' => 'Design',
		'children' => [
			[
				'name' => 'Snippets',
				'label' => 'widgets::core.title.snippets.list',
				'url' => route('backend.snippet.list'),
				'permissions' => 'snippet.index',
				'priority' => 200,
				'icon' => 'cutlery'
			],
			[
				'name' => 'Widgets',
				'label' => 'widgets::core.title.widgets.list',
				'url' => route('backend.widget.list'),
				'permissions' => 'widgets.index',
				'priority' => 300,
				'icon' => 'cubes'
			],
		]
	]
];