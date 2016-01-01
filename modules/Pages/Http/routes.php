<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::get('page/wysiwyg/{id}', [
        'as'   => 'pages.wysiwyg',
        'uses' => 'PageWysiwygController@getPageWysiwyg',
    ]);

    Route::controller('page', 'PageController', [
        'getIndex'   => 'page.list',
        'getCreate'  => 'page.create',
        'postCreate' => 'page.create.post',
        'getEdit'    => 'page.edit',
        'postEdit'   => 'page.edit.post',
        'postDelete' => 'page.delete',
    ]);

    Route::controller('layouts', 'LayoutController', [
        'getIndex'   => 'layout.list',
        'getCreate'  => 'layout.create',
        'postCreate' => 'layout.create.post',
        'getEdit'    => 'layout.edit',
        'postEdit'   => 'layout.edit.post',
        'postDelete' => 'layout.delete',
    ]);
});

Route::group(['as' => 'api.', 'middleware' => ['web', 'api']], function () {
    RouteAPI::post('layout', ['as' => 'layout.create', 'uses' => 'API\LayoutController@postCreate']);
    RouteAPI::put('layout', ['as' => 'layout.edit', 'uses' => 'API\LayoutController@postEdit']);
    RouteAPI::get('layout.rebuild', ['as' => 'layout.rebuild.get', 'uses' => 'API\LayoutController@getRebuildBlocks']);
    RouteAPI::get('layout.blocks', ['as' => 'layout.rebuild.get', 'uses' => 'API\LayoutController@getBlocks']);
    RouteAPI::get('layout.xeditable', [
        'as'   => 'layout.xeditable',
        'uses' => 'API\LayoutController@getListForXEditable',
    ]);

    RouteAPI::get('page.part', ['as' => 'page.part.get', 'uses' => 'API\PagePartController@getByPageId']);
    RouteAPI::post('page.part', ['as' => 'page.part.post', 'uses' => 'API\PagePartController@create']);
    RouteAPI::put('page.part/{id}', [
        'as'   => 'page.part.put',
        'uses' => 'API\PagePartController@update',
    ])->where('id', '[0-9]+');
    RouteAPI::delete('page.part/{id}', [
        'as'   => 'page.part.delete',
        'uses' => 'API\PagePartController@delete',
    ])->where('id', '[0-9]+');
    RouteAPI::post('page.part.reorder', ['as' => 'page.part.reorder', 'uses' => 'API\PagePartController@reorder']);

    RouteAPI::get('page.behavior.settings', [
        'as'   => 'page.behavior.settings',
        'uses' => 'API\PageBehaviorController@getSettings',
    ]);

    RouteAPI::get('page.children', ['as' => 'page.children', 'uses' => 'API\PageController@getChildren']);
    RouteAPI::get('page.reorder', ['as' => 'page.reorder', 'uses' => 'API\PageController@getReorder']);
    RouteAPI::post('page.reorder', ['as' => 'page.reorder', 'uses' => 'API\PageController@postReorder']);
    RouteAPI::get('page.search', ['as' => 'page.search', 'uses' => 'API\PageController@getSearch']);
    RouteAPI::post('page.changeStatus', [
        'as'   => 'page.change_status',
        'uses' => 'API\PageController@postChangeStatus',
    ]);
});
