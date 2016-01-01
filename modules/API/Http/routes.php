<?php

Route::group(['as' => 'api.', 'middleware' => ['web', 'api']], function () {
    RouteAPI::post('refresh.key', ['as' => 'refresh.key', 'uses' => 'API\KeysController@postRefresh']);
    RouteAPI::get('keys', ['as' => 'keys.list', 'uses' => 'API\KeysController@getKeys']);
    RouteAPI::put('key', ['as' => 'key.put', 'uses' => 'API\KeysController@putKey']);
    RouteAPI::delete('key', ['as' => 'key.delete', 'uses' => 'API\KeysController@deleteKey']);
});
