<?php
Route::group(['prefix' => CMS::backendPath()], function () {

	Route::get('/', ['as' => 'backend.dashboard', 'uses' => 'DashboardController@getIndex']);
});

Route::get('/api.dashboard.widget.list', ['as' => 'api.dashboard.widget.list', 'uses' => 'DashboardController@getWidgetList']);


Route::get('/api.dashboard.widget', ['as' => 'api.dashboard.widget.settings', 'uses' => 'API\DashboardController@getWidgetSettings']);
Route::put('/api.dashboard.widget', ['as' => 'api.dashboard.widget.put', 'uses' => 'API\DashboardController@putWidget']);
Route::post('/api.dashboard.widget', ['as' => 'api.dashboard.widget.post', 'uses' => 'API\DashboardController@postWidget']);
Route::delete('/api.dashboard.widget', ['as' => 'api.dashboard.widget.delete', 'uses' => 'API\DashboardController@deleteWidget']);