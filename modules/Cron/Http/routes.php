<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/cron', ['as' => 'backend.cron.list', 'uses' => 'CronController@getIndex']);
	Route::get('/cron/create', ['as' => 'backend.cron.create', 'uses' => 'CronController@getCreate']);
	Route::post('/cron/create', ['as' => 'backend.cron.create.post', 'uses' => 'CronController@postCreate']);
	Route::get('/cron/{id}/edit', ['as' => 'backend.cron.edit', 'uses' => 'CronController@getEdit']);
	Route::post('/cron/{id}/edit', ['as' => 'backend.cron.edit.post', 'uses' => 'CronController@postEdit']);
	Route::get('/cron/{id}/delete', ['as' => 'backend.cron.delete', 'uses' => 'CronController@getDelete']);

	Route::get('/cron/{id}/run', ['as' => 'backend.cron.run', 'uses' => 'CronController@getRun']);
});