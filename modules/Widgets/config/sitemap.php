<?php

return [
	[
		'name' => 'Design',
		'children' => [
			[
				'name' => 'Snippets',
				'url' => route('backend.snippet.list'),
				'permissions' => 'snippet.index',
				'priority' => 200,
				'icon' => 'cutlery'
			],
			[
				'name' => 'Widgets',
				'url' => route('backend.widget.list'),
				'permissions' => 'widgets.index',
				'priority' => 300,
				'icon' => 'cubes'
			],
		]
	]
];