<?php
Route::group(['prefix' => CMS::backendPath(), 'namespace' => 'Backend'], function () {
	Route::get('/page', ['as' => 'backend.page.list', 'uses' => 'PageController@index']);
	Route::get('/layout', ['as' => 'backend.layout.list', 'uses' => 'LayoutController@index']);
});

app('router')->before(function() {
	Route::get('{slug}', 'FrontendController@run')->where('slug', '(.*)?');
});