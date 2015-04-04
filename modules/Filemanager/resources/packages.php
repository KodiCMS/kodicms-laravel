<?php

use KodiCMS\CMS\Assets\Package;

Package::add('elfinder')
	->js('elfinder.lib', CMS::resourcesURL() . '/libs/elfinder/js/elfinder.min.js', 'global')
	->js('elfinder.' . Lang::getLocale(), CMS::resourcesURL() . '/libs/elfinder/js/i18n/elfinder.' . Lang::getLocale() . '.js', 'elfinder.lib')
	->css('elfinder.lib', CMS::resourcesURL() . '/libs/elfinder/css/elfinder.min.css');