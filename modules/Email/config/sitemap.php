<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Email',
				'label' => 'email::core.title.section',
				'icon' => 'envelope',
				'priority' => 400,
				'children' => [
					[
						'name' => 'Email templates',
						'label' => 'email::core.title.templates.list',
						'url' => route('backend.email.template.list'),
						'permissions' => 'email.templates.index',
						'icon' => 'envelope-o'
					],
					[
						'name' => 'Email types',
						'label' => 'email::core.title.types.list',
						'url' => route('backend.email.type.list'),
						'permissions' => 'email.types.index',
						'icon' => 'exchange'
					]
				]
			]
		]
	]
];