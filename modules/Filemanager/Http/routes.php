<?php

Route::group(['prefix' => \CMS::backendPath()], function ()
{
	Route::get('filemanager.popup', ['as' => 'backend.filemanager.popup', 'uses' => 'FilemanagerController@popup']);
	Route::get('filemanager', ['as' => 'backend.filemanager', 'uses' => 'FilemanagerController@show']);
});

RouteAPI::any('filemanager', ['as' => 'backend.filemanager.api', 'uses' => 'API\FilemanagerController@load']);