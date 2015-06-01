<?php

Package::add('weather')
	->js(NULL, resources_url() . '/libs/weather/js/weather.js', 'jquery');

Package::add('gridster')
	->js(NULL, resources_url() . '/libs/gridster/js/jquery.gridster.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/gridster/css/jquery.gridster.min.css');