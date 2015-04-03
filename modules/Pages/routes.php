<?php
app('router')->before(function() {
	Route::get('{slug}', 'FrontendController@run')->where('slug', '(.*)?');
});