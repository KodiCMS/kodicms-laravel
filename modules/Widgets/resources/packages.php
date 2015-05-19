<?php

use KodiCMS\CMS\Assets\Package;

Package::add('sortable')
	->js('sortable-library', resources_url() . '/libs/sortable/Sortable.min.js', 'jquery')
	->js(NULL, resources_url() . '/libs/sortable/jquery.binding.js', 'jquery');

Package::add('page-wysiwyg')
	->js(null, resources_url() . '/libs/page-wysiwyg/page-wysiwyg.js', ['jquery', 'sortable', 'libraries', 'core'])
	->css(null, resources_url() . '/libs/page-wysiwyg/style.css');