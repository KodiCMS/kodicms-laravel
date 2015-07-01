<?php

return [
	'public' => [
		'driver' => \KodiCMS\Filemanager\elFinder\VolumeLocalFileSystem::class,
		'path' => public_path('assets'),
		'URL' => url('public/assets'),
		'alias' => trans('filemanager::core.public'),
		'uploadMaxSize' => '32M',
		'mimeDetect' => 'internal',
		'imgLib' => 'gd',
		'attributes' => [
			[
				'pattern' => '/\.(tmb|quarantine|gitignore)/',
				'read' => false,
				'write' => false,
				'locked' => true,
				'hidden' => true
			],
		],

	]
];