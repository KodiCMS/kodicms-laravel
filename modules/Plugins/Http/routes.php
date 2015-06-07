<?php
Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::get('plugins', ['as' => 'backend.plugins.list', 'uses' => 'PluginController@getIndex']);
});

RouteAPI::get('plugins', ['as' => 'backend.api.plugins.list', 'uses' => 'API\PluginController@getList']);
RouteAPI::post('plugins', ['as' => 'backend.api.plugins.post', 'uses' => 'API\PluginController@changeStatus']);