<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::get('/settings', ['as' => 'settings', 'uses' => 'SystemController@settings']);
    Route::get('/about', ['as' => 'about', 'uses' => 'SystemController@about']);
    Route::get('/update', ['as' => 'update', 'uses' => 'SystemController@update']);
    Route::get('/phpinfo', ['as' => 'phpinfo', 'uses' => 'SystemController@phpInfo']);

    Route::get('/', ['as' => 'dashboard', 'uses' => 'SystemController@about']);
});

Route::group(['as' => 'api.', 'middleware' => ['web']], function () {
    RouteAPI::get('updates.check.new_version', [
        'as'   => 'update.check.new_version',
        'uses' => 'API\UpdateController@checkNewVersion',
    ]);
    RouteAPI::get('updates.check', ['as' => 'update.check', 'uses' => 'API\UpdateController@checkRemoteFiles']);
    RouteAPI::get('updates.diff', ['as' => 'update.check', 'uses' => 'API\UpdateController@diffFiles']);
    RouteAPI::post('settings.update', ['as' => 'settings.update', 'uses' => 'API\SettingsController@post']);
    RouteAPI::delete('cache.clear', ['as' => 'cache.clear', 'uses' => 'API\CacheController@deleteClear']);
});
