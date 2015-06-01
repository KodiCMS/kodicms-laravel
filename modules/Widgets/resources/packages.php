<?php

Package::add('page-wysiwyg')
	->js(null, backend_resources_url() . '/js/page-wysiwyg/page-wysiwyg.js', ['jquery', 'sortable', 'libraries', 'core'])
	->css(null, backend_resources_url() . '/js/page-wysiwyg/style.css');