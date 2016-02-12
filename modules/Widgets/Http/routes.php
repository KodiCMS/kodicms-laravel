<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::controller('snippets', 'SnippetController', [
        'getIndex'   => 'snippet.list',
        'getCreate'  => 'snippet.create',
        'postCreate' => 'snippet.create.post',
        'getEdit'    => 'snippet.edit',
        'postEdit'   => 'snippet.edit.post',
        'postDelete' => 'snippet.delete',
    ]);

    Route::get('widget/{type}', ['as' => 'widget.list.by_type', 'uses' => 'WidgetController@getIndex']);

    Route::controller('widget', 'WidgetController', [
        'getIndex'     => 'widget.list',
        'getLocation'  => 'widget.location',
        'postLocation' => 'widget.location.post',
        'getTemplate'  => 'widget.template',
        'getCreate'    => 'widget.create',
        'postCreate'   => 'widget.create.post',
        'getEdit'      => 'widget.edit',
        'postEdit'     => 'widget.edit.post',
        'postDelete'   => 'widget.delete',
        'getPopupList' => 'widget.popup_list',
    ]);

});

Route::group(['as' => 'api.', 'middleware' => ['web', 'api']], function () {
    RouteAPI::put('widget', ['as' => 'widget.place', 'uses' => 'API\WidgetController@putPlace']);
    RouteAPI::post('widget.set.template', [
        'as'   => 'widget.set.template',
        'uses' => 'API\WidgetController@setTemplate',
    ]);
    RouteAPI::post('page.widgets.reorder', [
        'as'   => 'page.widgets.reorder',
        'uses' => 'API\WidgetController@postReorder',
    ]);
    RouteAPI::post('snippet', ['as' => 'snippet.create', 'uses' => 'API\SnippetController@postCreate']);
    RouteAPI::put('snippet', ['as' => 'snippet.edit', 'uses' => 'API\SnippetController@postEdit']);
    RouteAPI::get('snippet.list', ['as' => 'snippet.list', 'uses' => 'API\SnippetController@getList']);
    RouteAPI::get('snippet.xeditable', [
        'as'   => 'snippet.xeditable',
        'uses' => 'API\SnippetController@getListForXEditable',
    ]);
});

Route::get('handler/{handler}', ['as' => 'widget.handler', 'uses' => 'HandlerController@getHandle', 'middleware' => ['web']]);
