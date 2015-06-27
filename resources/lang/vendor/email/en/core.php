<?php
return [
	'title' => [
		'section' => 'E-mail',
		'templates' => [
			'list' => 'Letters',
			'create' => 'New letter',
			'edit' => 'Edit letter',
		],
		'events' => [
			'list' => 'Post event',
			'create' => 'New event',
			'edit' => 'Edit event :title',
		],
	],
	'button' => [
		'events' => [
			'create' => 'Create event',
		],
		'templates' => [
			'create' => 'Write a letter',
		],
	],
	'field' => [
		'events' => [
			'name' => 'Event name',
			'code' => 'Event',
			'fields' => 'Parameters',
		],
		'templates' => [
			'email_event' => 'Post Event',
			'status' => 'Status',
			'use_queue' => 'The method of sending a message',
			'email_from' => 'From',
			'email_to' => 'Whom',
			'subject' => 'Theme',
			'message' => 'Text of letter',
			'message_type' => 'Message Type',
			'cc' => 'Copy',
			'bcc' => 'Bcc',
			'reply_to' => 'Reply to',
		],
		'actions' => 'Events',
	],
	'messages' => [
		'events' => [
			'created' => 'Event created',
			'updated' => 'Event updated',
			'deleted' => 'Event canceled',
			'not_found' => 'No events found',
			'job_not_found' => 'Event :name can not be found',
		],
		'templates' => [
			'created' => 'The template is created',
			'updated' => 'Template updated',
			'deleted' => 'Template deleted',
			'not_found' => 'Pattern not found',
		],
	],
	'tab' => [
		'general' => 'General information',
		'fields' => 'Parameters used',
		'message' => 'Text of letter',
		'message_info' => 'Template Collection of letters with a responsive design :link'
	],
	'templates' => [
		'title' => 'Related mail templates',
		'created_at' => 'Start time',
		'status' => 'Execution',
	],
	'statuses' => [
		0 => 'Inactive',
		1 => 'Most Active',
	],
	'queue' => [
		0 => 'Direct shipping',
		1 => 'Queuing',
	],
	'message_types' => [
		'html' => 'HTML',
		'text' => 'Plain text',
	],
	'template_data' => [
		'default_email' => 'Default e-mail address',
		'site_title' => 'Website Title',
		'site_description' => 'Site Description',
		'base_url' => 'Site Address (format :format)',
		'current_date' => 'The current date (in the format :format)',
		'current_time' => 'Current time (in the format :format)',
	],
	'settings' => [
		'title' => 'Mail Settings',
		'queue' => [
			'title' => 'Parameters message queue',
			'batch_size' => 'Number of messages sent in one shipment',
			'batch_help' => 'The number of emails to send out in each batch. This should be tuned to your servers abilities and the frequency of the cron.',
			'interval' => 'The interval between sending',
			'max_attempts' => 'Maximum number of attempts to send',
			'max_attempts_help' => 'The maximum number of attempts to send an email before giving up. An email may fail to send if the server is too busy, or there\'s a problem with the email itself.',
		],
		'default_email' => 'The default email address',
		'email_driver' => 'Driver',
		'test' => [
			'label' => 'To send a test message to save the settings',
			'btn' => 'Send a test email',
			'subject' => 'Test letter',
			'message' => 'Test report',
			'result_positive' => 'The test message was sent successfully',
			'result_negative' => 'The test message was not sent',
		],
		'sendmail' => [
			'path' => 'The path to the executable file',
			'placeholder' => 'For example: /usr/sbin/sendmail',
			'help' => 'The path to the program sendmail, is usually :path1 or :path2. :link',
		],
		'smtp' => [
			'host' => 'Server',
			'port' => 'Port',
			'username' => 'Username',
			'password' => 'Password',
			'encryption' => 'Encryption',
		],
		'mailgun' => [
			'domain' => 'Domain',
			'secret' => 'The secret key',
		],
		'mandrill' => [
			'secret' => 'The secret key',
		],
	],
	'jobs' => [
		'queue' => 'Sending pending mails',
		'clean' => 'Removing old messages from the queue',
	],
];