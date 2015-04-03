<?php
return [
	[
		'name' => 'Content',
		'icon' => 'pencil-square-o',
		'priority' => 200,
	],
	[
		'name' => 'System',
		'icon' => 'cog',
		'priority' => 8000,
		'children' => [
			[
				'name' => 'Information',
				'url' => route('backend.about'),
				'permissions' => 'system.information',
				'priority' => 90,
				'icon' => 'info-circle',
			],
			[
				'name' => 'Settings',
				'url' => route('backend.settings'),
				'permissions' => 'system.settings',
				'priority' => 100,
				'icon' => 'cog',
			]
		]

	],
];