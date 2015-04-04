<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Users',
				'translate' => 'users::user.sections.list',
				'url' => route('backend.user.list'),
				'permissions' => 'users.index',
				'priority' => 200,
				'icon' => 'user',
			],
			[
				'name' => 'Roles',
				'translate' => 'users::user.role.sections.list',
				'url' => route('backend.role.list'),
				'permissions' => 'roles.index',
				'priority' => 300,
				'icon' => 'group'
			]
		]
	]
];