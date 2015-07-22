<?php

Route::group(['prefix' => backend_url(), 'as' => 'backend.datasource.'], function ()
{
	Route::get('datasource/{id}', [
		'as' => 'list',
		'uses' => 'DatasourceController@getIndex'
	])->where('id', '[0-9]+');

	Route::controller('datasource/document', 'DocumentController', [
		'getCreate' => 'document.create',
		'postCreate' => 'document.create.post',
		'getEdit' => 'document.edit',
		'postEdit' => 'document.edit.post',
		'getRemove' => 'document.remove',
	]);

	Route::controller('datasource', 'DatasourceController', [
		'getCreate' => 'create',
		'postCreate' => 'create.post',
		'getEdit' => 'edit',
		'postEdit' => 'edit.post',
		'getRemove' => 'remove',
	]);
});