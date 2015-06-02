<?php

RouteAPI::post('refresh.key', ['as' => 'api.refresh.key', 'uses' => 'API\KeysController@postRefresh']);
RouteAPI::get('keys', ['as' => 'api.keys.list', 'uses' => 'API\KeysController@getKeys']);
RouteAPI::put('key', ['as' => 'api.key.put', 'uses' => 'API\KeysController@putKey']);
RouteAPI::delete('key', ['as' => 'api.key.delete', 'uses' => 'API\KeysController@deleteKey']);