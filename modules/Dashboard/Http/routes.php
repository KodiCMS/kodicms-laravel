<?php
Route::group(['prefix' => CMS::backendPath()], function ()
{
	Route::get('/', ['as' => 'backend.dashboard', 'uses' => 'DashboardController@getIndex']);
});

RouteAPI::get('dashboard.widget.list', ['as' => 'api.dashboard.widget.list', 'uses' => 'DashboardController@getWidgetList']);
RouteAPI::get('dashboard.widget', ['as' => 'api.dashboard.widget.settings', 'uses' => 'API\DashboardController@getWidgetSettings']);
RouteAPI::put('dashboard.widget', ['as' => 'api.dashboard.widget.put', 'uses' => 'API\DashboardController@putWidget']);
RouteAPI::post('dashboard.widget', ['as' => 'api.dashboard.widget.post', 'uses' => 'API\DashboardController@postWidget']);
RouteAPI::delete('dashboard.widget', ['as' => 'api.dashboard.widget.delete', 'uses' => 'API\DashboardController@deleteWidget']);