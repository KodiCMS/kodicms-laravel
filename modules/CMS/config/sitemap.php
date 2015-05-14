<?php
return [
	[
		'name' => 'Content',
		'label' => 'cms::core.title.content',
		'icon' => 'pencil-square-o',
		'priority' => 200,
	],
	[
		'name' => 'Design',
		'label' => 'cms::core.title.design',
		'icon' => 'desktop',
		'priority' => 7000
	],
	[
		'name' => 'System',
		'label' => 'cms::core.title.system',
		'icon' => 'cog',
		'priority' => 8000,
		'children' => [
			[
				'name' => 'Information',
				'label' => 'cms::core.title.about',
				'url' => route('backend.about'),
				'permissions' => 'system.about',
				'priority' => 90,
				'icon' => 'info-circle',
			],
			[
				'name' => 'Settings',
				'label' => 'cms::core.title.settings',
				'url' => route('backend.settings'),
				'permissions' => 'system.settings',
				'priority' => 100,
				'icon' => 'cog',
			]
		]
	],
];