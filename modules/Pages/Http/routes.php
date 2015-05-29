<?php

use KodiCMS\CMS\Helpers\URL;

Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::get('page/wysiwyg/{id}', [
		'as' => 'backend.pages.wysiwyg',
		'uses' => 'PageWysiwygController@getPageWysiwyg'
	]);

	Route::controller('page', 'PageController', [
		'getIndex' => 'backend.page.list',
		'getCreate' => 'backend.page.create',
		'postCreate' => 'backend.page.create.post',
		'getEdit' => 'backend.page.edit',
		'postEdit' => 'backend.page.edit.post',
		'getDelete' => 'backend.page.delete',
	]);

	Route::controller('layouts', 'LayoutController', [
		'getIndex' => 'backend.layout.list',
		'getCreate' => 'backend.layout.create',
		'postCreate' => 'backend.layout.create.post',
		'getEdit' => 'backend.layout.edit',
		'postEdit' => 'backend.layout.edit.post',
		'getDelete' => 'backend.layout.delete',
	]);
});

RouteAPI::post('layout', ['as' => 'api.layout.create', 'uses' => 'API\LayoutController@postCreate']);
RouteAPI::put('layout', ['as' => 'api.layout.edit', 'uses' => 'API\LayoutController@postEdit']);
RouteAPI::get('layout.rebuild', ['as' => 'api.layout.rebuild.get', 'uses' => 'API\LayoutController@getRebuildBlocks']);
RouteAPI::get('layout.blocks', ['as' => 'api.layout.rebuild.get', 'uses' => 'API\LayoutController@getBlocks']);
RouteAPI::get('layout.xeditable', ['as' => 'api.layout.xeditable', 'uses' => 'API\LayoutController@getListForXEditable']);

RouteAPI::get('page.part', ['as' => 'api.page.part.get', 'uses' => 'API\PagePartController@getByPageId']);
RouteAPI::post('page.part', ['as' => 'api.page.part.post', 'uses' => 'API\PagePartController@create']);
RouteAPI::put('page.part/{id}', ['as' => 'api.page.part.put', 'uses' => 'API\PagePartController@update'])->where('id', '[0-9]+');
RouteAPI::delete('page.part/{id}', ['as' => 'api.page.part.delete', 'uses' => 'API\PagePartController@delete'])->where('id', '[0-9]+');
RouteAPI::post('page.part.reorder', ['as' => 'api.page.part.reorder', 'uses' => 'API\PagePartController@reorder']);

RouteAPI::get('page.children', ['as' => 'api.page.children', 'uses' => 'API\PageController@getChildren']);
RouteAPI::get('page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@getReorder']);
RouteAPI::post('page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@postReorder']);
RouteAPI::get('page.search', ['as' => 'api.page.search', 'uses' => 'API\PageController@getSearch']);
RouteAPI::post('page.changeStatus', ['as' => 'api.page.change_status', 'uses' => 'API\PageController@postChangeStatus']);

app('router')->before(function()
{
	// TODO: добавить возвожность использовать суффикс
	/*Route::get('{slug}{suffix}', [
		'as' => 'frontend.url',
		'uses' => 'KodiCMS\Pages\Http\Controllers\FrontendController@run'
	])
		->where('slug', '(.*)?')
		->where('suffix', URL::getSuffix());*/

	Route::get('{slug}', [
		'as' => 'frontend.url',
		'uses' => 'KodiCMS\Pages\Http\Controllers\FrontendController@run'
	])
		->where('slug', '(.*)?');
});