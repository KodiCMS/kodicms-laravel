<?php

Route::get('{slug}', [
	'uses' => '\KodiCMS\Installer\Http\Controllers\InstallerController@run'
])
	->where('slug', '(.*)?');