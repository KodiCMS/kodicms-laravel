<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::get('/cron', ['as' => 'cron.list', 'uses' => 'CronController@getIndex']);
    Route::get('/cron/create', ['as' => 'cron.create', 'uses' => 'CronController@getCreate']);
    Route::post('/cron/create', ['as' => 'cron.create.post', 'uses' => 'CronController@postCreate']);
    Route::get('/cron/{id}/edit', ['as' => 'cron.edit', 'uses' => 'CronController@getEdit']);
    Route::post('/cron/{id}/edit', ['as' => 'cron.edit.post', 'uses' => 'CronController@postEdit']);
    Route::post('/cron/{id}/delete', ['as' => 'cron.delete', 'uses' => 'CronController@postDelete']);
    Route::get('/cron/{id}/run', ['as' => 'cron.run', 'uses' => 'CronController@getRun']);
});
