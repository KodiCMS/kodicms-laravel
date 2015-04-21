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
		'getIndex' => 'backend.widget.list'
	]);
});