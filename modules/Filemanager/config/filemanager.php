<?php

return [
	'public' => [
		'driver' => \KodiCMS\Filemanager\elFinder\Connector::FILE_SYSTEM,
		'path' => public_path('assets'), // TODO: поправить пути
		'URL' => url('public/assets'),
		'alias' => trans('filemanager::filemanager.public'),
		'uploadMaxSize' => '10M',
		'mimeDetect' => 'internal',
		'imgLib' => 'gd',
	]
];