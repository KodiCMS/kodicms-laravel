<?php

$locale = Lang::getLocale();

Package::add('carousel')
	->css(null, url('demo/libs/Sequence/css/style.css'), 'bootstrap')
	->js(null, url('demo/libs/Sequence/js/jquery.sequence-min.js'), 'jquery');

Package::add('slider')
	->css(null, url('demo/libs/slick/slick.css'), 'bootstrap')
	->js(null, url('demo/libs/slick/slick.min.js'), 'jquery');

Package::add('holder')
	->js(null, url('demo/js/holder.js'), 'jquery');

Package::add('isotope')
	->js(null, url('demo/js/jquery.isotope.js'), 'jquery');

Package::add('rating')
	->js(null, url('demo/js/jquery.raty.js'), 'jquery');

Package::add('demo-assets')
	->js('global', url('demo/js/core.js'), 'bootstrap')
	->css('core', url('demo/css/core.css'), 'bootstrap')
	->css('animate', url('demo/css/animate.css'), 'bootstrap');
