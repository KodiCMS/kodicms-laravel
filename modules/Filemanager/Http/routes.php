<?php

Route::group(['prefix' => backend_url(), 'as' => 'backend.'], function ()
{
	Route::get('filemanager.popup', ['as' => 'filemanager.popup', 'uses' => 'FilemanagerController@popup']);
	Route::get('filemanager', ['as' => 'filemanager', 'uses' => 'FilemanagerController@show']);
});

RouteAPI::any('filemanager', ['as' => 'backend.filemanager.api', 'uses' => 'API\FilemanagerController@load']);