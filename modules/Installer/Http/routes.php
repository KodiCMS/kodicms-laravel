<?php

RouteAPI::post('installer.databaseCheck', [
    'as'   => 'api.installer.databaseCheck',
    'uses' => 'API\InstallerController@postDatabaseCheck',
]);

Route::get('install', ['uses' => 'InstallerController@run', 'as' => 'installer.get']);
Route::post('install', ['uses' => 'InstallerController@install', 'as' => 'installer.post']);