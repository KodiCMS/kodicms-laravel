<?php

RouteAPI::post('installer.databaseCheck', [
    'as'   => 'api.installer.databaseCheck',
    'uses' => 'API\InstallerController@postDatabaseCheck',
    'middleware' => ['web', 'api']
]);

Route::get('install', ['uses' => 'InstallerController@run', 'as' => 'installer.get', 'middleware' => ['web']]);
Route::post('install', ['uses' => 'InstallerController@install', 'as' => 'installer.post', 'middleware' => ['web']]);
