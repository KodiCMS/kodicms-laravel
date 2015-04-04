<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/cron', ['as' => 'backend.cron.list', 'uses' => 'CronController@index']);
});