<?php

use KodiCMS\CMS\Assets\Package;
use KodiCMS\CMS\Helpers\WYSIWYG;

Package::add('core')
	->css('global', CMS::backendResourcesURL() . '/css/common.css')
	->js(NULL, CMS::backendResourcesURL() . '/js/core.min.js', 'backbone')
	->js('global', CMS::backendResourcesURL() . '/js/backend.min.js', 'core');

Package::add('jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/jquery.min.js');

Package::add('bootstrap')
	->js(NULL, CMS::backendResourcesURL() . '/libs/bootstrap-3.3.1/dist/js/bootstrap.min.js', 'jquery');

Package::add('underscore')
	->js(NULL, CMS::backendResourcesURL() . '/libs/underscore-min.js', 'jquery');

Package::add('backbone')
	->js(NULL, CMS::backendResourcesURL() . '/libs/backbone-min.js', 'underscore');

Package::add('jquery-ui')
	->js(NULL, CMS::backendResourcesURL() . '/libs/jquery-ui/js/jquery-ui.min.js', 'jquery')
	->css(NULL, CMS::backendResourcesURL() . '/libs/jquery-ui/css/jquery-ui.min.css', 'jquery');

Package::add('notify')
	->js(NULL, CMS::backendResourcesURL() . '/libs/pnotify/jquery.pnotify.min.js', 'jquery')
	->css(NULL, CMS::backendResourcesURL() . '/libs/pnotify/jquery.pnotify.default.css', 'jquery');

Package::add('dropzone')
	->css(NULL, CMS::backendResourcesURL() . '/libs/dropzone/min/basic.min.css', 'jquery')
	->css(NULL, CMS::backendResourcesURL() . '/libs/dropzone/min/dropzone.min.css', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/dropzone/min/dropzone.min.js', 'jquery');

Package::add('fancybox')
	->css(NULL, CMS::backendResourcesURL() . '/libs/fancybox/jquery.fancybox.css', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/fancybox/jquery.fancybox.pack.js', 'jquery');

Package::add('datepicker')
	->css(NULL, CMS::backendResourcesURL() . '/libs/datepicker/jquery.datetimepicker.css', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/datepicker/jquery.datetimepicker.min.js', 'jquery');

Package::add('prism')
	->css(NULL, CMS::backendResourcesURL() . '/libs/prismjs/prism.css', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/prismjs/prism.js', 'jquery');

Package::add('colorpicker')
	->css(NULL, CMS::backendResourcesURL() . '/libs/colorpicker/css/colorpicker.css', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/libs/colorpicker/js/colorpicker.js', 'jquery');

Package::add('editable')
	->js(NULL, CMS::backendResourcesURL() . '/libs/bootstrap-editable-1.5.1/js/bootstrap-editable.min.js', 'bootstrap');

Package::add('nestable')
	->js(NULL, CMS::backendResourcesURL() . '/libs/nestable/jquery.nestable.min.js', 'bootstrap');

Package::add('ace')
	->js('ace-library', CMS::backendResourcesURL() . '/libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/js/ace.js', 'jquery');

Package::add('steps')
	->js(NULL, CMS::backendResourcesURL() . '/libs/steps/jquery.steps.min.js', 'jquery');

Package::add('chart')
	->js(NULL, CMS::backendResourcesURL() . '/libs/highcharts/highcharts.js', 'jquery');

Package::add('ckeditor')
	->js('ckeditor-library', CMS::backendResourcesURL() . '/libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, CMS::backendResourcesURL() . '/js/ckeditor.js', 'jquery');

Package::add('select2')
	->js(NULL, CMS::backendResourcesURL() . '/libs/select2/select2.min.js', 'jquery')
	->js(NULL . Lang::getLocale(), CMS::backendResourcesURL() . '/libs/select2/select2_locale_' . Lang::getLocale() . '.js', 'select2');

Package::add('validate')
	->js(NULL, CMS::backendResourcesURL() . '/libs/validation/jquery.validate.min.js', 'jquery')
	->js(NULL . Lang::getLocale(), CMS::backendResourcesURL() . '/libs/validation/localization/messages_' . Lang::getLocale() . '.min.js', 'validate');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::TYPE_CODE);
WYSIWYG::add('ckeditor', 'CKEditor');