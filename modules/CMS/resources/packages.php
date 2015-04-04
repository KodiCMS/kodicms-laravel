<?php

use KodiCMS\CMS\Assets\Package;
use KodiCMS\CMS\Helpers\WYSIWYG;

Package::add('libraries')
	->js(NULL, CMS::resourcesURL() . '/js/libraries.js');

Package::add('core')
	->css('global', CMS::resourcesURL() . '/css/common.css')
	->js(NULL, CMS::resourcesURL() . '/js/backend.js', 'libraries');

Package::add('jquery')
	->js(NULL, CMS::resourcesURL() . '/libs/jquery.min.js');

Package::add('bootstrap')
	->js(NULL, CMS::resourcesURL() . '/libs/bootstrap-3.3.1/dist/js/bootstrap.min.js', ['jquery', 'libraries']);

Package::add('underscore')
	->js(NULL, CMS::resourcesURL() . '/libs/underscore-min.js', ['jquery', 'libraries']);

Package::add('backbone')
	->js(NULL, CMS::resourcesURL() . '/libs/backbone-min.js', ['underscore', 'libraries']);

Package::add('jquery-ui')
	->js(NULL, CMS::resourcesURL() . '/libs/jquery-ui/js/jquery-ui.min.js', ['jquery', 'libraries'])
	->css(NULL, CMS::resourcesURL() . '/libs/jquery-ui/css/jquery-ui.min.css');

Package::add('notify')
	->js(NULL, CMS::resourcesURL() . '/libs/pnotify/jquery.pnotify.min.js', 'jquery')
	->css(NULL, CMS::resourcesURL() . '/libs/pnotify/jquery.pnotify.default.css');

Package::add('dropzone')
	->js(NULL, CMS::resourcesURL() . '/libs/dropzone/min/dropzone.min.js', ['jquery', 'libraries'])
	->css(NULL, CMS::resourcesURL() . '/libs/dropzone/min/basic.min.css', 'jquery')
	->css(NULL, CMS::resourcesURL() . '/libs/dropzone/min/dropzone.min.css', 'jquery');

Package::add('fancybox')
	->js(NULL, CMS::resourcesURL() . '/libs/fancybox/jquery.fancybox.pack.js', 'jquery')
	->css(NULL, CMS::resourcesURL() . '/libs/fancybox/jquery.fancybox.css', 'jquery');

Package::add('datepicker')
	->js(NULL, CMS::resourcesURL() . '/libs/datepicker/jquery.datetimepicker.min.js', 'jquery')
	->css(NULL, CMS::resourcesURL() . '/libs/datepicker/jquery.datetimepicker.css', 'jquery');

Package::add('prism')
	->js(NULL, CMS::resourcesURL() . '/libs/prismjs/prism.js', 'jquery')
	->css(NULL, CMS::resourcesURL() . '/libs/prismjs/prism.css', 'jquery');

Package::add('colorpicker')
	->css(NULL, CMS::resourcesURL() . '/libs/colorpicker/css/colorpicker.css', 'jquery')
	->js(NULL, CMS::resourcesURL() . '/libs/colorpicker/js/colorpicker.js', 'jquery');

Package::add('editable')
	->js(NULL, CMS::resourcesURL() . '/libs/bootstrap-editable-1.5.1/js/bootstrap-editable.min.js', 'bootstrap');

Package::add('nestable')
	->js(NULL, CMS::resourcesURL() . '/libs/nestable/jquery.nestable.min.js', 'bootstrap');

Package::add('steps')
	->js(NULL, CMS::resourcesURL() . '/libs/steps/jquery.steps.min.js', 'jquery');

Package::add('chart')
	->js(NULL, CMS::resourcesURL() . '/libs/highcharts/highcharts.js', 'jquery');

Package::add('select2')
	->js(NULL, CMS::resourcesURL() . '/libs/select2/select2.min.js', 'jquery')
	->js('select2' . Lang::getLocale(), CMS::resourcesURL() . '/libs/select2/select2_locale_' . Lang::getLocale() . '.js', ['select2', 'libraries']);

Package::add('validate')
	//->js(NULL, CMS::resourcesURL() . '/libs/validation/jquery.validate.min.js', 'jquery')
	->js('validate' . Lang::getLocale(), CMS::resourcesURL() . '/libs/validation/localization/messages_' . Lang::getLocale() . '.min.js', 'validate');

Package::add('ckeditor')
	->js('ckeditor-library', CMS::resourcesURL() . '/libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/js/ckeditor.js', 'jquery');

Package::add('ace')
	->js('ace-library', CMS::resourcesURL() . '/libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/js/ace.js', 'jquery');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::TYPE_CODE);
WYSIWYG::add('ckeditor', 'CKEditor');