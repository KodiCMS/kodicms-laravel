<?php

RouteAPI::get('api.notifications.list', [
    'as'   => 'api.notifications.list',
    'uses' => 'API\NotificationsController@getList',
    'middleware' => ['web', 'api']
]);

RouteAPI::delete('api.notification.read', [
    'as'   => 'api.notifications.read',
    'uses' => 'API\NotificationsController@deleteRead',
    'middleware' => ['web', 'api']
]);
