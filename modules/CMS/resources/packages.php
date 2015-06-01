<?php

$locale = Lang::getLocale();

Package::add('libraries')
	->js(NULL, resources_url() . '/js/libraries.js')
	->js("moment-{$locale}", resources_url() . "/libs/moment/locale/{$locale}.js", 'libraries');

Package::add('core')
	->css('global', resources_url() . '/css/common.css')
	->js(NULL, resources_url() . '/js/backend.js', 'libraries');

Package::add('jquery')
	->js(NULL, resources_url() . '/libs/jquery/dist/jquery.min.js');

Package::add('bootstrap')
	->css(NULL, resources_url() . '/libs/bootstrap/dist/css/bootstrap.min.css')
	->js(NULL, resources_url() . '/libs/bootstrap/dist/js/bootstrap.min.js', 'jquery');

Package::add('bootstrap-theme')
	->css(NULL, resources_url() . '/libs/bootstrap/dist/css/bootstrap-theme.min.css', 'bootstrap');

Package::add('underscore')
	->js(NULL, resources_url() . '/libs/underscore/underscore-min.js', 'jquery');

Package::add('backbone')
	->js(NULL, resources_url() . '/libs/backbone/backbone-min.js', 'underscore');

Package::add('jquery-ui')
	->js(NULL, resources_url() . '/libs/jquery-ui/jquery-ui.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/jquery-ui/themes/ui-lightness/jquery-ui.min.css');

Package::add('dropzone')
	->js(NULL, resources_url() . '/libs/dropzone/dist/min/dropzone.min.js', 'jquery')
	->css('dropzone-basic', resources_url() . '/libs/dropzone/dist/min/basic.min.css')
	->css(NULL, resources_url() . '/libs/dropzone/dist/min/dropzone.min.css');

Package::add('datepicker')
	->js(NULL, resources_url() . '/libs/datetimepicker/jquery.datetimepicker.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/datetimepicker/jquery.datetimepicker.css');

Package::add('editable')
	->js(NULL, resources_url() . '/libs/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js', ['jquery', 'bootstrap'])
	->css(NULL, resources_url() . '/libs/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css');

Package::add('nestable')
	->js(NULL, resources_url() . '/libs/nestable/jquery.nestable.js', 'jquery');

Package::add('steps')
	->js(NULL, resources_url() . '/libs/jquery.steps/build/jquery.steps.min.js', 'jquery');

Package::add('noty')
	->js(NULL, resources_url() . '/libs/noty/js/noty/packaged/jquery.noty.packaged.min.js', 'jquery');

Package::add('select2')
	->js(NULL, resources_url() . '/libs/select2/dist/js/select2.min.js', 'jquery')
	->js("select2-{$locale}", resources_url() . "/libs/select2/dist/js/i18n/{$locale}.js", 'select2');

Package::add('sortable')
	->js(NULL, resources_url() . '/libs/Sortable/Sortable.min.js', 'jquery')
	->js('sortable-binding', resources_url() . '/libs/Sortable/jquery.binding.js', 'jquery');

Package::add('validate')
	->js(NULL, resources_url() . '/libs/jquery-validation/dist/jquery.validate.min.js', 'jquery')
	->js("validate-{$locale}", resources_url() . "/libs/jquery-validation/src/localization/messages_{$locale}.js", 'validate');

Package::add('moment')
	->js(NULL, resources_url() . '/libs/moment/min/moment.min.js', 'jquery')
	->js("moment-{$locale}", resources_url() . "/libs/moment/locale/{$locale}.js", 'moment');

Package::add('ckeditor')
	->js('ckeditor-library', resources_url() . '/libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ckeditor.js', 'ckeditor-library')
	->js("ckeditor-{$locale}", resources_url() . "/libs/ckeditor/lang/{$locale}.js", 'validate');

Package::add('ace')
	->js('ace-library', resources_url() . '/libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ace.js', 'ace-library');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::code());
WYSIWYG::add('ckeditor', 'CKEditor');