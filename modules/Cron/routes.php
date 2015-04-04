<?php

Route::group(['prefix' => \CMS::backendPath(), 'namespace' => 'Backend'], function () {

	Route::get('/cron', ['as' => 'backend.cron.list', 'uses' => 'CronController@index']);
});