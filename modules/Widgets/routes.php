<?php

Route::group(['prefix' => CMS::backendPath()], function () {
	Route::get('/snippet', ['as' => 'backend.snippet.list', 'uses' => 'SnippetController@index']);
	Route::get('/widget', ['as' => 'backend.widget.list', 'uses' => 'WidgetController@index']);
});