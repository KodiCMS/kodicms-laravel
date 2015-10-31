<?php

Route::group(['as' => 'api.datasource.'], function () {
    RouteAPI::get('datasource.tags', ['as' => 'tags', 'uses' => 'Api\TagsController@getTags']);
});