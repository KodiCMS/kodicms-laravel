<?php

Route::group(['prefix' => \CMS::backendPath()], function () {

	Route::get('user/{id}/edit', ['as' => 'backend.user.edit', 'uses' => 'UserController@getEdit'])->where('id', '[0-9]+');
	Route::post('user/{id}/edit', ['as' => 'backend.user.edit.post', 'uses' => 'UserController@postEdit'])->where('id', '[0-9]+');
	Route::get('user/{id}/delete', ['as' => 'backend.user.delete', 'uses' => 'UserController@getDelete'])->where('id', '[0-9]+');
	Route::get('user/{id}/profile', ['as' => 'backend.user.profile', 'uses' => 'UserController@getProfile'])->where('id', '[0-9]+');
	Route::get('user/profile', ['as' => 'backend.user.current_profile', 'uses' => 'UserController@getProfile']);

	Route::controller('user', 'UserController', [
		'getIndex' => 'backend.user.list',
		'getCreate' => 'backend.user.create',
		'postCreate' => 'backend.user.create.post',
	]);

	Route::controller('role', 'RoleController', [
		'getIndex' => 'backend.role.list'
	]);

	Route::controller('auth', 'Auth\AuthController', [
		'getLogin' => 'auth.login',
		'getLogout' => 'auth.logout',
		'postLogin' => 'auth.login.post'
	]);

	Route::controller('password', 'Auth\PasswordController', [
		'getEmail' => 'auth.password',
		'postEmail' => 'auth.password.post'
	]);
});

Route::get('/api.user.meta', ['as' => 'api.user.meta.get', 'uses' => 'API\UserMetaController@getData']);
Route::post('/api.user.meta', ['as' => 'api.user.meta.post', 'uses' => 'API\UserMetaController@postData']);
Route::delete('/api.user.meta', ['as' => 'api.user.meta.delete', 'uses' => 'API\UserMetaController@deleteData']);