<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Users',
				'url' => route('backend.users.list'),
				'permissions' => 'users.index',
				'priority' => 200,
				'icon' => 'user',
				'divider' => TRUE,
			],
			[
				'name' => 'Roles',
				'url' => route('backend.roles.list'),
				'permissions' => 'roles.index',
				'priority' => 300,
				'icon' => 'group'
			]
		]
	]
];