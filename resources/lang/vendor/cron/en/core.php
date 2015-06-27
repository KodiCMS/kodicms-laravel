<?php
return [
	'title'    => [
		'list' => 'Tasks',
		'cron' => [
			'create' 	=> 'The new challenge',
			'edit'   	=> 'Editing task :title',
		]
	],
	'field'    => [
		'name'       => 'Title',
		'task_name'  => 'Function',
		'date_start' => 'First run',
		'date_end'   => 'Last Run',
		'last_run'   => 'Last Run',
		'next_run'   => 'Next start',
		'interval'   => 'Interval',
		'crontime'   => 'Line crontime',
		'status'     => 'Status',
		'actions'    => 'Actions',
		'attempts'   => 'Attempts',
	],
	'button'   => [
		'create' 		 => 'Create a task',
		'run'   		 => 'Start job',
	],
	'tab'      => [
		'general' 	 => 'General information',
		'options' 	 => 'OPTIONS',
	],
	'jobs'     => [
		'test' 			 => 'Testing cron',
	],
	'interval' => [
		'minute' 		 => 'Minute',
		'hour'   		 => 'Hour',
		'day'    		 => 'Day',
		'week'   		 => 'Week',
		'month'  		 => 'Month',
		'year'   		 => 'Year',
		'or'     		 => 'Or',
	],
	'crontab'  => [
		'help'    	 => 'Description',
		'weekday' 	 => 'Day of the week (0 - 7) (Sunday is 0 or 7)',
		'month'   	 => 'Month (1 - 12)',
		'day'     	 => 'The day (1 - 31)',
		'hour'    	 => 'Hour (0 - 23)',
		'minute'  	 => 'Minute (0 - 59)',
	],
	'messages' => [
		'created'   => 'Task created',
		'updated'   => 'Task updated',
		'deleted'   => 'The task removed',
		'runned'    => 'The problem started',
		'not_found' => 'Problem Found',
		'empty' 		=> 'There are no problems',
	],
	'statuses' => [
		-1 => 'Not done',
		1  => 'The new challenge',
		2  => 'Running Now',
		3  => 'Task completed',
	],
	'logs'     => [
		'title'      => 'The history of execution',
		'created_at' => 'Start time',
		'status'     => 'Execution',
	],
	'settings' => [
		'title'  => 'Setting objectives',
		'info'   => 'If you use cron crontab necessary to add the following line:',
		'agents' => [
			'system' => 'System',
			'cron'   => 'Crontab',
		],
	],
];