<?php
Route::group(['prefix' => CMS::backendPath()], function () {

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

Route::get('/api.layout.rebuild', ['as' => 'api.layout.rebuild.get', 'uses' => 'API\LayoutController@getRebuildBlocks']);

Route::get('/api.page.part', ['as' => 'api.page.part.get', 'uses' => 'API\PagePartController@getByPageId']);
Route::post('/api.page.part', ['as' => 'api.page.part.post', 'uses' => 'API\PagePartController@create']);
Route::put('/api.page.part/{id}', ['as' => 'api.page.part.put', 'uses' => 'API\PagePartController@update'])->where('id', '[0-9]+');
Route::delete('/api.page.part/{id}', ['as' => 'api.page.part.delete', 'uses' => 'API\PagePartController@delete'])->where('id', '[0-9]+');
Route::post('/api.page.part.reorder', ['as' => 'api.page.part.reorder', 'uses' => 'API\PagePartController@reorder']);

Route::get('/api.page.children', ['as' => 'api.page.children', 'uses' => 'API\PageController@getChildren']);
Route::get('/api.page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@getReorder']);
Route::post('/api.page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@postReorder']);
Route::get('/api.page.search', ['as' => 'api.page.search', 'uses' => 'API\PageController@getSearch']);
Route::post('/api.page.changeStatus', ['as' => 'api.page.change_status', 'uses' => 'API\PageController@postChangeStatus']);

app('router')->before(function() {
	Route::get('{slug}', 'KodiCMS\Pages\Http\Controllers\FrontendController@run')->where('slug', '(.*)?');
});