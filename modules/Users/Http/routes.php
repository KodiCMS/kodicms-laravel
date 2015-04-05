<?php

Route::group(['prefix' => \CMS::backendPath()], function () {

	Route::controller('user', 'UserController', [
		'getIndex' => 'backend.user.list',
		'getEdit' => 'backend.user.edit',
		'getProfile' => 'backend.user.profile',
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