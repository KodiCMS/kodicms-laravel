<?php

Route::group(['prefix' => \CMS::backendPath()], function () {
	Route::get('/email/template', ['as' => 'backend.email.template.list', 'uses' => 'EmailTemplateController@getIndex']);
	Route::get('/email/template/create', ['as' => 'backend.email.template.create', 'uses' => 'EmailTemplateController@getCreate']);
	Route::post('/email/template/create', ['as' => 'backend.email.template.create.post', 'uses' => 'EmailTemplateController@postCreate']);
	Route::get('/email/template/{id}/edit', ['as' => 'backend.email.template.edit', 'uses' => 'EmailTemplateController@getEdit']);
	Route::post('/email/template/{id}/edit', ['as' => 'backend.email.template.edit.post', 'uses' => 'EmailTemplateController@postEdit']);
	Route::get('/email/template/{id}/delete', ['as' => 'backend.email.template.delete', 'uses' => 'EmailTemplateController@getDelete']);

	Route::get('/email/event', ['as' => 'backend.email.event.list', 'uses' => 'EmailEventController@getIndex']);
	Route::get('/email/event/create', ['as' => 'backend.email.event.create', 'uses' => 'EmailEventController@getCreate']);
	Route::post('/email/event/create', ['as' => 'backend.email.event.create.post', 'uses' => 'EmailEventController@postCreate']);
	Route::get('/email/event/{id}/edit', ['as' => 'backend.email.event.edit', 'uses' => 'EmailEventController@getEdit']);
	Route::post('/email/event/{id}/edit', ['as' => 'backend.email.event.edit.post', 'uses' => 'EmailEventController@postEdit']);
	Route::get('/email/event/{id}/delete', ['as' => 'backend.email.event.delete', 'uses' => 'EmailEventController@getDelete']);
});

Route::get('/api.email-events.options', ['as' => 'api.email.event.options', 'uses' => 'API\EmailEventController@getOptions']);
Route::post('/api.email.send', ['as' => 'api.email.send', 'uses' => 'API\EmailEventController@postSend']);