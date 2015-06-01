<?php

Package::add('weather')
	->js(NULL, resources_url() . '/libs/weather/weather.js', 'jquery');

Package::add('gridster')
	->js(NULL, resources_url() . '/libs/gridster/dist/jquery.gridster.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/gridster/dist/jquery.gridster.min.css');