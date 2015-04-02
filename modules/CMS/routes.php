<?php

Route::group(['prefix' => Config::get('cms.admin_dir_name'), 'as' => 'backend'], function () {
	Route::get('/', 'WelcomeController@index');


	Route::get('{slug}', 'System\ErrorController@show')->where('slug', '(.*)?');
});