<?php

Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::controller('snippets', 'SnippetController', [
		'getIndex' => 'backend.snippet.list',
		'getCreate' => 'backend.snippet.create',
		'postCreate' => 'backend.snippet.create.post',
		'getEdit' => 'backend.snippet.edit',
		'postEdit' => 'backend.snippet.edit.post',
		'postDelete' => 'backend.snippet.delete',
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
		'postDelete' => 'backend.widget.delete',
		'getPopupList' => 'backend.widget.popup_list'
	]);

	Route::get('handler/{$id}', ['as' => 'widget.handler', 'uses' => 'HandlerController@handle']);
});

RouteAPI::put('widget', ['as' => 'api.widget.place', 'uses' => 'API\WidgetController@putPlace']);

RouteAPI::post('widget.set.template', ['as' => 'api.widget.set.template', 'uses' => 'API\WidgetController@setTemplate']);

RouteAPI::post('page.widgets.reorder', ['as' => 'api.page.widgets.reorder', 'uses' => 'API\WidgetController@postReorder']);

RouteAPI::post('snippet', ['as' => 'api.snippet.create', 'uses' => 'API\SnippetController@postCreate']);
RouteAPI::put('snippet', ['as' => 'api.snippet.edit', 'uses' => 'API\SnippetController@postEdit']);
RouteAPI::get('snippet.list', ['as' => 'api.snippet.list', 'uses' => 'API\SnippetController@getList']);
RouteAPI::get('snippet.xeditable', ['as' => 'api.snippet.xeditable', 'uses' => 'API\SnippetController@getListForXEditable']);