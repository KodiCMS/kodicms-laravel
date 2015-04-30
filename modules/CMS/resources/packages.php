<?php

use KodiCMS\CMS\Assets\Package;
use KodiCMS\CMS\Helpers\WYSIWYG;

Package::add('libraries')
	->js(NULL, resources_url() . '/js/libraries.js');

Package::add('core')
	->css('global', resources_url() . '/css/common.css')
	->js(NULL, resources_url() . '/js/backend.js', 'libraries');

Package::add('jquery')
	->js(NULL, resources_url() . '/libs/jquery.min.js');

Package::add('bootstrap')
	->js(NULL, resources_url() . '/libs/bootstrap-3.3.4/dist/js/bootstrap.min.js', 'jquery');

Package::add('underscore')
	->js(NULL, resources_url() . '/libs/underscore-min.js', 'jquery');

Package::add('backbone')
	->js(NULL, resources_url() . '/libs/backbone-min.js', 'underscore');

Package::add('jquery-ui')
	->js(NULL, resources_url() . '/libs/jquery-ui/js/jquery-ui.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/jquery-ui/css/jquery-ui.min.css');

Package::add('notify')
	->js(NULL, resources_url() . '/libs/pnotify/jquery.pnotify.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/pnotify/jquery.pnotify.default.css');

Package::add('dropzone')
	->js(NULL, resources_url() . '/libs/dropzone/min/dropzone.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/dropzone/min/basic.min.css')
	->css(NULL, resources_url() . '/libs/dropzone/min/dropzone.min.css');

Package::add('fancybox')
	->js(NULL, resources_url() . '/libs/fancybox/jquery.fancybox.pack.js', 'jquery')
	->css(NULL, resources_url() . '/libs/fancybox/jquery.fancybox.css');

Package::add('datepicker')
	->js(NULL, resources_url() . '/libs/datepicker/jquery.datetimepicker.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/datepicker/jquery.datetimepicker.css');

Package::add('prism')
	->js(NULL, resources_url() . '/libs/prismjs/prism.js')
	->css(NULL, resources_url() . '/libs/prismjs/prism.css');

Package::add('colorpicker')
	->css(NULL, resources_url() . '/libs/colorpicker/css/colorpicker.css')
	->js(NULL, resources_url() . '/libs/colorpicker/js/colorpicker.js', 'jquery');

Package::add('editable')
	->js(NULL, resources_url() . '/libs/bootstrap-editable-1.5.1/js/bootstrap-editable.min.js', ['jquery', 'bootstrap']);

Package::add('nestable')
	->js(NULL, resources_url() . '/libs/nestable/jquery.nestable.min.js', 'jquery');

Package::add('steps')
	->js(NULL, resources_url() . '/libs/steps/jquery.steps.min.js', 'jquery');

Package::add('chart')
	->js(NULL, resources_url() . '/libs/highcharts/highcharts.js', 'jquery');

Package::add('select2')
	->js(NULL, resources_url() . '/libs/select2/select2.min.js', 'jquery')
	->js('select2' . Lang::getLocale(), resources_url() . '/libs/select2/select2_locale_' . Lang::getLocale() . '.js', 'select2');

Package::add('validate')
	->js(NULL, resources_url() . '/libs/validation/jquery.validate.min.js', 'jquery')
	->js('validate' . Lang::getLocale(), resources_url() . '/libs/validation/localization/messages_' . Lang::getLocale() . '.min.js', 'validate');

Package::add('ckeditor')
	->js('ckeditor-library', resources_url() . '/libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ckeditor.js', 'ckeditor-library');

Package::add('ace')
	->js('ace-library', resources_url() . '/libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ace.js', 'ace-library');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::TYPE_CODE);
WYSIWYG::add('ckeditor', 'CKEditor');