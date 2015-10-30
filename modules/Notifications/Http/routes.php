<?php

RouteAPI::get('api.notifications.list', [
    'as'   => 'api.notifications.list',
    'uses' => 'API\NotificationsController@getList',
]);
RouteAPI::delete('api.notification.read', [
    'as'   => 'api.notifications.read',
    'uses' => 'API\NotificationsController@deleteRead',
]);