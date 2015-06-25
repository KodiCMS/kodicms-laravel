<?php

Route::group(['prefix' => CMS::backendPath(), 'as' => 'backend.'], function ()
{
	Route::get('user/{id}/edit', ['as' => 'user.edit', 'uses' => 'UserController@getEdit'])->where('id', '[0-9]+');
	Route::post('user/{id}/edit', ['as' => 'user.edit.post', 'uses' => 'UserController@postEdit'])->where('id', '[0-9]+');
	Route::post('user/{id}/delete', ['as' => 'user.delete', 'uses' => 'UserController@postDelete'])->where('id', '[0-9]+');
	Route::get('user/{id}/profile', ['as' => 'user.profile', 'uses' => 'UserController@getProfile'])->where('id', '[0-9]+');
	Route::get('user/profile', ['as' => 'user.current_profile', 'uses' => 'UserController@getProfile']);

	Route::controller('user', 'UserController', [
		'getIndex' => 'user.list',
		'getCreate' => 'user.create',
		'postCreate' => 'user.create.post',
	]);

	Route::get('role/{id}/edit', ['as' => 'role.edit', 'uses' => 'RoleController@getEdit'])->where('id', '[0-9]+');
	Route::post('role/{id}/edit', ['as' => 'role.edit.post', 'uses' => 'RoleController@postEdit'])->where('id', '[0-9]+');
	Route::post('role/{id}/delete', ['as' => 'role.delete', 'uses' => 'RoleController@postDelete'])->where('id', '[0-9]+');

	Route::controller('role', 'RoleController', [
		'getIndex' => 'role.list',
		'getCreate' => 'role.create',
		'postCreate' => 'role.create.post'
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

Route::group(['as' => 'api.user.'], function ()
{
	RouteAPI::get('api.user.list', ['as' => 'list.get', 'uses' => 'API\UserController@getUsers']);
	RouteAPI::get('api.user.like', ['as' => 'like.get', 'uses' => 'API\UserController@getLike']);
	RouteAPI::get('api.user.roles', ['as' => 'roles.get', 'uses' => 'API\UserController@getRoles']);

	RouteAPI::get('api.user.meta', ['as' => 'meta.get', 'uses' => 'API\UserMetaController@getData']);
	RouteAPI::post('api.user.meta', ['as' => 'meta.post', 'uses' => 'API\UserMetaController@postData']);
	RouteAPI::delete('api.user.meta', ['as' => 'meta.delete', 'uses' => 'API\UserMetaController@deleteData']);
});

RouteAPI::get('api.roles', ['as' => 'api.roles.get', 'uses' => 'API\RoleController@getAll']);

Route::group(['prefix' => 'reflink', 'as' => 'reflink.'], function ()
{
	Route::get('', ['as' => 'form', 'uses' => 'ReflinkController@getForm']);
	Route::post('', ['as' => 'form.post', 'uses' => 'ReflinkController@postForm']);
	Route::get('complete', ['as' => 'complete', 'uses' => 'ReflinkController@complete']);
	Route::get('{token}', ['as' => 'token', 'uses' => 'ReflinkController@handle'])
		->where('token', '[a-z0-9]+');
});