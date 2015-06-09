<?php

Route::group(['prefix' => CMS::backendPath(), 'as' => 'backend.email.'], function ()
{
	Route::get('/email/template', ['as' => 'template.list', 'uses' => 'EmailTemplateController@getIndex']);
	Route::get('/email/template/create', ['as' => 'template.create', 'uses' => 'EmailTemplateController@getCreate']);
	Route::post('/email/template/create', ['as' => 'template.create.post', 'uses' => 'EmailTemplateController@postCreate']);
	Route::get('/email/template/{id}/edit', ['as' => 'template.edit', 'uses' => 'EmailTemplateController@getEdit']);
	Route::post('/email/template/{id}/edit', ['as' => 'template.edit.post', 'uses' => 'EmailTemplateController@postEdit']);
	Route::post('/email/template/{id}/delete', ['as' => 'template.delete', 'uses' => 'EmailTemplateController@postDelete']);

	Route::get('/email/event', ['as' => 'event.list', 'uses' => 'EmailEventController@getIndex']);
	Route::get('/email/event/create', ['as' => 'event.create', 'uses' => 'EmailEventController@getCreate']);
	Route::post('/email/event/create', ['as' => 'event.create.post', 'uses' => 'EmailEventController@postCreate']);
	Route::get('/email/event/{id}/edit', ['as' => 'event.edit', 'uses' => 'EmailEventController@getEdit']);
	Route::post('/email/event/{id}/edit', ['as' => 'event.edit.post', 'uses' => 'EmailEventController@postEdit']);
	Route::post('/email/event/{id}/delete', ['as' => 'event.delete', 'uses' => 'EmailEventController@postDelete']);
});

Route::group(['prefix' => CMS::backendPath(), 'as' => 'api.email.'], function ()
{
	RouteAPI::get('email-events.options', ['as' => 'event.options', 'uses' => 'API\EmailEventController@getOptions']);
	RouteAPI::post('email.send', ['as' => 'send', 'uses' => 'API\EmailEventController@postSend']);
});
