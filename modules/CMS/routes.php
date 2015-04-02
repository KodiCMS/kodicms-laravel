<?php

Route::group(['prefix' => Config::get('cms.admin_dir_name')], function () {
	Route::get('/', 'WelcomeController@index');
});

Route::get('/', 'WelcomeController@index');