<?php
Route::group(['prefix' => CMS::backendPath()], function () {

	Route::get('datasource/{id}', [
		'as' => 'backend.datasource.list',
		'uses' => 'DatasourceController@getIndex'
	])->where('id', '[0-9]+');

	Route::controller('datasource/document', 'DocumentController', [
		'getCreate' => 'backend.datasource.document.create',
		'postCreate' => 'backend.datasource.document.create.post',
		'getEdit' => 'backend.datasource.document.edit',
		'postEdit' => 'backend.datasource.document.edit.post',
		'getRemove' => 'backend.datasource.document.remove',
	]);

	Route::controller('datasource', 'DatasourceController', [
		'getCreate' => 'backend.datasource.create',
		'postCreate' => 'backend.datasource.create.post',
		'getEdit' => 'backend.datasource.edit',
		'postEdit' => 'backend.datasource.edit.post',
		'getRemove' => 'backend.datasource.remove',
	]);
});