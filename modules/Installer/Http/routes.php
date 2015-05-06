<?php



app('router')->before(function() {
	Route::group(['namespace' => 'KodiCMS\CMS\Http\Controllers', 'prefix' => CMS::backendPath()], function () {
		Route::get('cms/{file}.{ext}', 'System\VirtualMediaLinksController@find')
			->where('file', '.*')
			->where('ext', '(css|js|png|jpg|gif|otf|eot|svg|ttf|woff)');
	});

	Route::group(['namespace' => 'KodiCMS\Installer\Http\Controllers'], function () {
		Route::get('api.installer.databaseCheck', ['as' => 'api.installer.databaseCheck', 'uses' => 'API\InstallerController@databaseCheck']);

		Route::get('{slug}', [
			'uses' => 'InstallerController@run'
		])
			->where('slug', '(.*)?');

		Route::post('{slug}', [
			'uses' => 'InstallerController@install'
		])
			->where('slug', '(.*)?');
	});
});