<?php

Route::group(['prefix' => \CMS::backendPath(), 'namespace' => 'Backend'], function () {

	Route::get('/user', ['as' => 'backend.user.list', 'uses' => 'UserController@index']);
	Route::get('/role', ['as' => 'backend.role.list', 'uses' => 'RoleController@index']);

	Route::get('/profile', ['as' => 'backend.users.profile', 'uses' => 'UserController@profile']);
});