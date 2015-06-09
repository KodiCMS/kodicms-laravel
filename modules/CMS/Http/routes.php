<?php

Route::group(['prefix' => CMS::backendPath(), 'as' => 'backend.'], function ()
{
	Route::get('/settings', ['as' => 'settings', 'uses' => 'SystemController@settings']);
	Route::get('/about', ['as' => 'about', 'uses' => 'SystemController@about']);
	Route::get('/update', ['as' => 'update', 'uses' => 'SystemController@update']);
	Route::get('/phpinfo', ['as' => 'phpinfo', 'uses' => 'SystemController@phpInfo']);
});

Route::group(['as' => 'api.'], function ()
{
	RouteAPI::post('settings.update', ['as' => 'settings.update', 'uses' => 'API\SettingsController@post']);
	RouteAPI::delete('cache.clear', ['as' => 'cache.clear', 'uses' => 'API\CacheController@deleteClear']);
});