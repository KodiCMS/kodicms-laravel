<?php
Route::group(['prefix' => backend_url(), 'as' => 'backend.plugins.'], function () {
    Route::get('plugins', ['as' => 'list', 'uses' => 'PluginController@getIndex']);
    Route::get('plugins/settings/{plugin}', ['as' => 'settings.get', 'uses' => 'PluginController@getSettings']);
    Route::post('plugins/settings/{plugin}', ['as' => 'settings.post', 'uses' => 'PluginController@postSettings']);
});

RouteAPI::get('plugins', ['as' => 'api.plugins.list', 'uses' => 'API\PluginController@getList']);
RouteAPI::post('plugins', ['as' => 'api.plugins.post', 'uses' => 'API\PluginController@changeStatus']);