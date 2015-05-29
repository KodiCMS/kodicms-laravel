<?php

RouteAPI::post('refresh.key', ['as' => 'api.refresh.key', 'uses' => 'API\KeysController@postRefresh']);