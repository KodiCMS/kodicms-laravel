<?php

Route::group(['prefix' => \CMS::backendPath(), 'namespace' => 'Backend'], function () {

	Route::get('/users', ['as' => 'backend.users.list', 'uses' => 'UsersController@index']);
	Route::get('/roles', ['as' => 'backend.roles.list', 'uses' => 'RolesController@index']);

	Route::get('/profile', ['as' => 'backend.users.profile', 'uses' => 'UsersController@profile']);
});