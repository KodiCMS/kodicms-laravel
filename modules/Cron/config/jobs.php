<?php

return [
	'cron:test' => [
		'label'  => trans('cron::core.jobs.test'),
		'action' => 'KodiCMS\Cron\Support\Crontab@crontabTest',
	]
];