<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.plugins.', 'middleware' => ['web']], function () {
    Route::get('plugins', ['as' => 'list', 'uses' => 'PluginController@getIndex']);
    Route::get('plugins/settings/{plugin}', ['as' => 'settings.get', 'uses' => 'PluginController@getSettings']);
    Route::post('plugins/settings/{plugin}', ['as' => 'settings.post', 'uses' => 'PluginController@postSettings']);
});

RouteAPI::get('plugins', ['as' => 'api.plugins.list', 'uses' => 'API\PluginController@getList', 'middleware' => ['web', 'api']]);
RouteAPI::post('plugins', ['as' => 'api.plugins.post', 'uses' => 'API\PluginController@changeStatus', 'middleware' => ['web', 'api']]);
