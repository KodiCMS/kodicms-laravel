<?php

Route::get('{slug}', 'FrontendController@run')->where('slug', '(.*)?');