<?php

Route::group(['prefix' => CMS::backendPath()], function () {

	Route::controller('snippets', 'SnippetController', [
		'getIndex' => 'backend.snippet.list',
		'getCreate' => 'backend.snippet.create',
		'postCreate' => 'backend.snippet.create.post',
		'getEdit' => 'backend.snippet.edit',
		'postEdit' => 'backend.snippet.edit.post',
		'getDelete' => 'backend.snippet.delete',
	]);

	Route::controller('widget', 'WidgetController', [
		'getIndex' => 'backend.widget.list',
		'getLocation' => 'backend.widget.location',
		'postLocation' => 'backend.widget.location.post',
		'getTemplate' => 'backend.widget.template',
		'getCreate' => 'backend.widget.create',
		'postCreate' => 'backend.widget.create.post',
		'getEdit' => 'backend.widget.edit',
		'postEdit' => 'backend.widget.edit.post',
		'getDelete' => 'backend.widget.delete',
		'getPopupList' => 'backend.widget.popup_list'
	]);

	Route::get('handler/{$id}', ['as' => 'widget.handler', 'uses' => 'HandlerController@handle']);
});

Route::put('api.widget', ['as' => 'api.widget.place', 'uses' => 'API\WidgetController@putPlace']);
Route::post('/api.page.widgets.reorder', ['as' => 'api.page.widgets.reorder', 'uses' => 'API\WidgetController@postReorder']);


Route::post('api.snippet', ['as' => 'api.snippet.create', 'uses' => 'API\SnippetController@postCreate']);
Route::put('api.snippet', ['as' => 'api.snippet.edit', 'uses' => 'API\SnippetController@postEdit']);
Route::get('api.snippet.list', ['as' => 'api.snippet.list', 'uses' => 'API\SnippetController@getList']);