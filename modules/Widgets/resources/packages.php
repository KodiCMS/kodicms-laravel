<?php

Package::add('page-wysiwyg')
	->js(null, resources_url() . 'cms/js/page-wysiwyg/page-wysiwyg.js', ['jquery', 'sortable', 'libraries', 'core'])
	->css(null, resources_url() . 'cms/js/page-wysiwyg/style.css');