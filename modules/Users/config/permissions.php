<?php
return [
	'backend' => [
		[
			'action' => 'user.list',
			'description' => 'View users'
		],
		[
			'action' => 'user.create',
			'description' => 'Add new users'
		],
		[
			'action' => 'user.edit',
			'description' => 'Edit users'
		],
		[
			'action' => 'user.view.permissions',
			'description' => 'View user permissions'
		],
		[
			'action' => 'user.user.change_roles',
			'description' => 'Change user roles'
		],
		[
			'action' => 'user.change_password',
			'description' => 'Change password'
		],
		[
			'action' => 'user.delete',
			'description' => 'Delete users'
		],
		[
			'action' => 'role.list',
			'description' => 'View roles'
		],
		[
			'action' => 'role.create',
			'description' => 'Add new roles'
		],
		[
			'action' => 'role.edit',
			'description' => 'Edit roles'
		],
		[
			'action' => 'role.change_permissions',
			'description' => 'Change role permissions'
		],
		[
			'action' => 'role.delete',
			'description' => 'Delete roles'
		],
	],

];