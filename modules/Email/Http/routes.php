<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/email/template', ['as' => 'backend.email.template.list', 'uses' => 'EmailTemplateController@getIndex']);
	Route::get('/email/template/create', ['as' => 'backend.email.template.create', 'uses' => 'EmailTemplateController@getCreate']);
	Route::post('/email/template/create', ['as' => 'backend.email.template.create.post', 'uses' => 'EmailTemplateController@postCreate']);
	Route::get('/email/template/{id}/edit', ['as' => 'backend.email.template.edit', 'uses' => 'EmailTemplateController@getEdit']);
	Route::post('/email/template/{id}/edit', ['as' => 'backend.email.template.edit.post', 'uses' => 'EmailTemplateController@postEdit']);
	Route::get('/email/template/{id}/delete', ['as' => 'backend.email.template.delete', 'uses' => 'EmailTemplateController@getDelete']);

	Route::get('/email/type', ['as' => 'backend.email.type.list', 'uses' => 'EmailTypeController@getIndex']);
	Route::get('/email/type/create', ['as' => 'backend.email.type.create', 'uses' => 'EmailTypeController@getCreate']);
	Route::post('/email/type/create', ['as' => 'backend.email.type.create.post', 'uses' => 'EmailTypeController@postCreate']);
	Route::get('/email/type/{id}/edit', ['as' => 'backend.email.type.edit', 'uses' => 'EmailTypeController@getEdit']);
	Route::post('/email/type/{id}/edit', ['as' => 'backend.email.type.edit.post', 'uses' => 'EmailTypeController@postEdit']);
	Route::get('/email/type/{id}/delete', ['as' => 'backend.email.type.delete', 'uses' => 'EmailTypeController@getDelete']);
});

Route::get('/api.email-types.options', ['as' => 'api.email.type.options', 'uses' => 'API\EmailTypeController@getOptions']);
Route::post('/api.email.send', ['as' => 'api.email.send', 'uses' => 'API\EmailTypeController@postSend']);