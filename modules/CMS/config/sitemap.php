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
//				'url' => route('backendInformation'),
				'url' => url('backendInformation'),
				'permissions' => 'system.information',
				'priority' => 90,
				'icon' => 'info-circle',
			],
			[
				'name' => 'Settings',
//				'url' => route('backendSettings'),
				'url' => url('backendSettings'),
				'permissions' => 'system.settings',
				'priority' => 100,
				'icon' => 'cog',
			]
		]

	],
];