<?php

Route::get('page/non-database', [
	'uses' => 'TestController@getIndex'
]);