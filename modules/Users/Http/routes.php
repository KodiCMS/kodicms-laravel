<?php

Route::group(['prefix' => \CMS::backendPath()], function () {

	Route::controller('user', 'UserController', [
		'getIndex' => 'backend.user.list',
		'getProfile' => 'backend.user.profile',
	]);

	Route::controller('role', 'RoleController', [
		'getIndex' => 'backend.role.list'
	]);
});