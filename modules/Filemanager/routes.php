<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/filemanager', ['as' => 'backend.filemanager', 'uses' => 'FilemanagerController@show']);
});

Route::any('/api.filemanager', ['as' => 'backend.api.filemanager', 'uses' => 'API\FilemanagerController@load']);