<?php

Route::group(['prefix' => CMS::backendPath()], function () {
	Route::get('/settings', ['as' => 'backend.settings', 'uses' => 'SystemController@settings']);
	Route::get('/about', ['as' => 'backend.about', 'uses' => 'SystemController@about']);

	Route::get('/phpinfo', ['as' => 'backend.phpinfo', 'uses' => 'SystemController@phpInfo']);
});

Route::post('/api.settings.update', ['as' => 'api.settings.update', 'uses' => 'API\SettingsController@post']);
Route::delete('/api.cache.clear', ['as' => 'api.cache.clear', 'uses' => 'API\CacheController@deleteClear']);

app('router')->before(function() {
	Route::group(['namespace' => 'KodiCMS\CMS\Http\Controllers', 'prefix' => CMS::backendPath()], function () {
		Route::get('cms/{file}.{ext}', 'System\VirtualMediaLinksController@find')
			->where('file', '.*')
			->where('ext', '(css|js|png|jpg|gif|otf|eot|svg|ttf|woff)');
	});
});