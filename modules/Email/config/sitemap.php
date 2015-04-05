<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Email',
				'label' => 'email::core.sections.section',
				'icon' => 'envelope',
				'priority' => 400,
				'children' => [
					[
						'name' => 'Email templates',
						'label' => 'email::core.sections.templates.list',
						'url' => route('backend.email.template.list'),
						'permissions' => 'email.templates.index',
						'icon' => 'envelope-o'
					],
					[
						'name' => 'Email types',
						'label' => 'email::core.sections.types.list',
						'url' => route('backend.email.type.list'),
						'permissions' => 'email.types.index',
						'icon' => 'exchange'
					]
				]
			]
		]
	]
];