<?php

Route::group(['prefix' => \CMS::backendPath(), 'namespace' => 'Backend'], function () {

	Route::get('/filemanager', ['as' => 'backend.filemanager', 'uses' => 'FilemanagerController@show']);
});