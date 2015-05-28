<?php

Route::post('/api.refresh.key', ['as' => 'api.refresh.key', 'uses' => 'API\KeysController@postRefresh']);