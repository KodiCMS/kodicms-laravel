<?php

Route::group(['prefix' => \CMS::backendPath()], function () {

	Route::controller('user', 'UserController', [
		'getIndex' => 'backend.user.list',
		'getProfile' => 'backend.user.profile',
	]);

	Route::controller('role', 'RoleController', [
		'getIndex' => 'backend.role.list'
	]);

	Route::controller('auth', 'Auth\AuthController', [
		'getLogin' => 'auth.login',
		'postLogin' => 'auth.login.post'
	]);

	Route::controller('password', 'Auth\PasswordController', [
		'getEmail' => 'auth.password',
		'postEmail' => 'auth.password.post'
	]);
});