<?php

Route::group(['prefix' => backend_url_segment(), 'as' => 'backend.', 'middleware' => ['web']], function () {
    Route::get('/guide/{module}/{page}', ['as' => 'userguide.doc', 'uses' => 'UserguideController@getModule']);
    Route::get('/guide/{module}', ['as' => 'userguide.docs', 'uses' => 'UserguideController@getModule']);
    Route::get('/guide', ['as' => 'userguide', 'uses' => 'UserguideController@getIndex']);
});
