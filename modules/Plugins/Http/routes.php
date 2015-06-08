<?php
Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::get('plugins', ['as' => 'backend.plugins.list', 'uses' => 'PluginController@getIndex']);
	Route::get('plugins/settings/{plugin}', ['as' => 'backend.plugins.settings.get', 'uses' => 'PluginController@getSettings']);
	Route::post('plugins/settings/{plugin}', ['as' => 'backend.plugins.settings.post', 'uses' => 'PluginController@postSettings']);
});

RouteAPI::get('plugins', ['as' => 'backend.api.plugins.list', 'uses' => 'API\PluginController@getList']);
RouteAPI::post('plugins', ['as' => 'backend.api.plugins.post', 'uses' => 'API\PluginController@changeStatus']);