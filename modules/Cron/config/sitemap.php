<?php

return [
	[
		'name' => 'System',
		'children' => [
			[
				'name' => 'Cron jobs',
				'icon' => 'bolt',
				'url' => route('backend.cron.list'),
				'permissions' => 'cron.index',
				'priority' => 500,
			]
		]
	]
];