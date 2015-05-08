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
	]);

	Route::get('/api.layout.rebuild', ['as' => 'api.layout.rebuild.get', 'uses' => 'API\LayoutController@getRebuildBlocks']);
	Route::get('handler/{$id}', ['as' => 'widget.handler', 'uses' => 'HandlerController@handle']);
});