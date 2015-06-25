<?php

Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::controller('news', 'NewsController', [
		'getIndex' => 'backend.news.list',
		'getCreate' => 'backend.news.create',
		'postCreate' => 'backend.news.create.post',
		'getEdit' => 'backend.news.edit',
		'postEdit' => 'backend.news.edit.post',
		'getDelete' => 'backend.news.delete',
	]);
});