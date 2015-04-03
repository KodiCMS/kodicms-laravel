<?php

Route::group(['prefix' => Config::get('cms.admin_dir_name')], function () {

	Route::group(['namespace' => 'Backend'], function () {
		Route::get('/', ['as' => 'backendDashboard', 'uses' => 'DashboardController@index']);
	});

	Route::get('{slug}', ['as' => 'backendError', 'uses' => 'System\ErrorController@show'])
		->where('slug', '(.*)?');
});