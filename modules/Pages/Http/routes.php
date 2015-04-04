<?php
Route::group(['prefix' => CMS::backendPath()], function () {
	Route::get('/page', ['as' => 'backend.page.list', 'uses' => 'PageController@index']);

	Route::controller('layout', 'LayoutController', [
		'getIndex' => 'backend.layout.list'
	]);

});

app('router')->before(function() {
	Route::get('{slug}', 'KodiCMS\Pages\Http\Controllers\FrontendController@run')->where('slug', '(.*)?');
});