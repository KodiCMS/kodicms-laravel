<?php

PackageManager::add('weather')
    ->js(null, resources_url('/libs/weather/js/weather.js'), 'jquery');

PackageManager::add('gridster')
    ->js(null, resources_url('/libs/gridster/js/jquery.gridster.min.js'), 'jquery')
    ->css(null, resources_url('/libs/gridster/css/jquery.gridster.min.css'));
