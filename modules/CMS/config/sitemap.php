<?php
return [
	[
		'name' => 'Dashboard',
		'translate' => 'cms:core.sections.dashboard',
		'icon' => 'dashboard',
		'url' => route('backend.dashboard'),
		'priority' => 0,
	],
	[
		'name' => 'Content',
		'translate' => 'cms:core.sections.content',
		'icon' => 'pencil-square-o',
		'priority' => 200,
	],
	[
		'name' => 'Design',
		'icon' => 'desktop',
		'priority' => 7000
	],
	[
		'name' => 'System',
		'translate' => 'cms:core.sections.system',
		'icon' => 'cog',
		'priority' => 8000,
		'children' => [
			[
				'name' => 'Information',
				'translate' => 'cms:core.sections.about',
				'url' => route('backend.about'),
				'permissions' => 'system.about',
				'priority' => 90,
				'icon' => 'info-circle',
			],
			[
				'name' => 'Settings',
				'translate' => 'cms:core.sections.settings',
				'url' => route('backend.settings'),
				'permissions' => 'system.settings',
				'priority' => 100,
				'icon' => 'cog',
			]
		]
	],
];