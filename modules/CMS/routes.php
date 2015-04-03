<?php

Route::group(['prefix' => CMS::backendPath()], function () {
	Route::group(['namespace' => 'Backend'], function () {
		Route::get('/', ['as' => 'backend.dashboard', 'uses' => 'DashboardController@index']);

		Route::get('/settings', ['as' => 'backend.settings', 'uses' => 'SystemController@settings']);
		Route::get('/about', ['as' => 'backend.about', 'uses' => 'SystemController@about']);
	});
});

app('router')->before(function() {
	Route::group(['namespace' => 'KodiCMS\CMS\Http\Controllers', 'prefix' => CMS::backendPath()], function () {
		Route::get('{slug}', [
			'as' => 'backendError',
			'uses' => 'System\ErrorController@show'
		])
			->where('slug', '(.*)?');
	});
});