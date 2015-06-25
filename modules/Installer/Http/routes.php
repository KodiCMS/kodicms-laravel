<?php

RouteAPI::get('installer.databaseCheck', ['as' => 'api.installer.databaseCheck', 'uses' => 'API\InstallerController@databaseCheck']);

Route::get('install', ['uses' => 'InstallerController@run', 'as' => 'installer.get']);
Route::post('install', ['uses' => 'InstallerController@install', 'as' => 'installer.post']);

Route::get('{slug}', [
	'uses' => 'InstallerController@error',
	'as' => 'installer.error'
])->where('slug', '(.*)?');