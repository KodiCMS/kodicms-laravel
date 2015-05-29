<?php

app('router')->before(function()
{
	// TODO: решить проблему дублирования роута
	Route::group(['namespace' => 'KodiCMS\CMS\Http\Controllers', 'prefix' => CMS::backendPath()], function ()
	{
		Route::get('cms/{file}.{ext}', 'System\VirtualMediaLinksController@find')
			->where('file', '.*')
			->where('ext', '(css|js|png|jpg|gif|otf|eot|svg|ttf|woff)');
	});

	Route::group(['namespace' => 'KodiCMS\Installer\Http\Controllers'], function ()
	{
		RouteAPI::get('installer.databaseCheck', ['as' => 'api.installer.databaseCheck', 'uses' => 'API\InstallerController@databaseCheck']);

		Route::get('install', ['uses' => 'InstallerController@run', 'as' => 'installer.get']);
		Route::post('install', ['uses' => 'InstallerController@install', 'as' => 'installer.post']);

		Route::get('{slug}', [
			'uses' => 'InstallerController@error',
			'as' => 'installer.error'
		])->where('slug', '(.*)?');

	});
});