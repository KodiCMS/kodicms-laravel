<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Email',
				'icon' => 'envelope',
				'priority' => 400,
				'children' => [
					[
						'name' => 'Email templates',
						'url' => route('backend.email.template.list'),
						'permissions' => 'email.templates.index',
						'icon' => 'envelope-o'
					],
					[
						'name' => 'Email types',
						'url' => route('backend.email.type.list'),
						'permissions' => 'email.types.index',
						'icon' => 'exchange'
					]
				]
			]
		]
	]
];