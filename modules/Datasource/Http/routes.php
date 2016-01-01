<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.datasource.', 'middleware' => ['web']], function () {
    Route::get('datasource/{id?}', [
        'as'   => 'list',
        'uses' => 'DatasourceController@getIndex',
    ])->where('id', '[0-9]+');

    Route::controller('datasource/field', 'FieldController', [
        'getCreate'   => 'field.create',
        'postCreate'  => 'field.create.post',
        'getEdit'     => 'field.edit',
        'postEdit'    => 'field.edit.post',
        'getLocation' => 'field.location',
    ]);

    Route::controller('datasource/document', 'DocumentController', [
        'getCreate'  => 'document.create',
        'postCreate' => 'document.create.post',
        'getEdit'    => 'document.edit',
        'postEdit'   => 'document.edit.post',
        'getRemove'  => 'document.remove',
    ]);

    Route::controller('datasource', 'DatasourceController', [
        'getCreate'  => 'create',
        'postCreate' => 'create.post',
        'getEdit'    => 'edit',
        'postEdit'   => 'edit.post',
        'getRemove'  => 'remove',
    ]);
});

Route::group(['as' => 'api.datasource.', 'middleware' => ['web', 'api']], function () {
    RouteAPI::get('datasource.headline', ['as' => 'headline', 'uses' => 'API\SectionController@getHeadline']);
    RouteAPI::post('datasource.field.visible', [
        'as'   => 'field.visible.set',
        'uses' => 'API\FieldController@setVisible',
    ]);
    RouteAPI::delete('datasource.field.visible', [
        'as'   => 'field.visible.delete',
        'uses' => 'API\FieldController@setInvisible',
    ]);
    RouteAPI::delete('datasource.field', ['as' => 'field.delete', 'uses' => 'API\FieldController@deleteField']);
    RouteAPI::post('datasource.document.remove', [
        'as'   => 'document.remove',
        'uses' => 'API\DocumentController@deleteDelete',
    ]);
    RouteAPI::get('datasource.document.find', ['as' => 'document.find', 'uses' => 'API\DocumentController@getFind']);
});
