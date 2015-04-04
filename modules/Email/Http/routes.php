<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/email', ['as' => 'backend.email.template.list', 'uses' => 'EmailController@index']);
	Route::get('/email/type', ['as' => 'backend.email.type.list', 'uses' => 'EmailTypeController@index']);
});