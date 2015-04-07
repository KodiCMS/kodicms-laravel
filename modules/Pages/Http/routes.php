<?php
Route::group(['prefix' => CMS::backendPath()], function () {

	Route::controller('page', 'PageController', [
		'getIndex' => 'backend.page.list',
		'getCreate' => 'backend.page.add',
		'getEdit' => 'backend.page.edit',
		'postEdit' => 'backend.page.edit.post',
		'postDelete' => 'backend.page.delete',
	]);

	Route::controller('layout', 'LayoutController', [
		'getIndex' => 'backend.layout.list'
	]);
});

Route::get('/api.page.children', ['as' => 'api.page.children', 'uses' => 'API\PageController@getChildren']);
Route::get('/api.page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@getReorder']);
Route::post('/api.page.reorder', ['as' => 'api.page.reorder', 'uses' => 'API\PageController@postReorder']);
Route::get('/api.page.search', ['as' => 'api.page.search', 'uses' => 'API\PageController@getSearch']);
Route::post('/api.page.changeStatus', ['as' => 'api.page.change_status', 'uses' => 'API\PageController@postChangeStatus']);

app('router')->before(function() {
	Route::get('{slug}', 'KodiCMS\Pages\Http\Controllers\FrontendController@run')->where('slug', '(.*)?');
});