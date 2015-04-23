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

	Route::get('role/{id}/edit', ['as' => 'backend.role.edit', 'uses' => 'RoleController@getEdit'])->where('id', '[0-9]+');
	Route::post('role/{id}/edit', ['as' => 'backend.role.edit.post', 'uses' => 'RoleController@postEdit'])->where('id', '[0-9]+');
	Route::get('role/{id}/delete', ['as' => 'backend.role.delete', 'uses' => 'RoleController@getDelete'])->where('id', '[0-9]+');

	Route::controller('role', 'RoleController', [
		'getIndex' => 'backend.role.list',
		'getCreate' => 'backend.role.create',
		'postCreate' => 'backend.role.create.post',
	]);

	Route::controller('message', 'MessageController', [
		'getIndex' => 'backend.message.list',
		'getCreate' => 'backend.message.create',
		'postCreate' => 'backend.message.create.post',
		'getRead' => 'backend.message.read',
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

Route::get('/api.user.list', ['as' => 'api.user.list.get', 'uses' => 'API\UserController@getUsers']);
Route::get('/api.user.like', ['as' => 'api.user.like.get', 'uses' => 'API\UserController@getLike']);
Route::get('/api.user.roles', ['as' => 'api.user.roles.get', 'uses' => 'API\UserController@getRoles']);
Route::get('/api.roles', ['as' => 'api.roles.get', 'uses' => 'API\RoleController@getAll']);

Route::get('/api.user.meta', ['as' => 'api.user.meta.get', 'uses' => 'API\UserMetaController@getData']);
Route::post('/api.user.meta', ['as' => 'api.user.meta.post', 'uses' => 'API\UserMetaController@postData']);
Route::delete('/api.user.meta', ['as' => 'api.user.meta.delete', 'uses' => 'API\UserMetaController@deleteData']);


Route::post('/api.user.message', ['as' => 'api.user.message.post', 'uses' => 'API\UserMessageController@postMessage']);
Route::delete('/api.user.message', ['as' => 'api.user.message.delete', 'uses' => 'API\UserMessageController@deleteMessage']);