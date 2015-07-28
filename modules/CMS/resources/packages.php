<?php

$locale = Lang::getLocale();

Package::add('libraries')
	->js(NULL, resources_url() . '/js/libraries.js')
	->js("moment-{$locale}", resources_url() . "/libs/moment/js/{$locale}.js", 'libraries');

Package::add('core')
	->css('global', resources_url() . '/css/app.css')
	->js(NULL, resources_url() . '/js/backend.js', 'libraries');

Package::add('jquery')
	->js(NULL, resources_url() . '/libs/jquery/js/jquery.min.js');

Package::add('bootstrap')
	->css(NULL, resources_url() . '/libs/bootstrap/css/bootstrap.min.css')
	->js(NULL, resources_url() . '/libs/bootstrap/js/bootstrap.min.js', 'jquery');

Package::add('bootstrap-theme')
	->css(NULL, resources_url() . '/libs/bootstrap/css/bootstrap-theme.min.css', 'bootstrap');

Package::add('underscore')
	->js(NULL, resources_url() . '/libs/underscore/js/underscore-min.js', 'jquery');

Package::add('backbone')
	->js(NULL, resources_url() . '/libs/backbone/js/backbone-min.js', 'underscore');

Package::add('jquery-ui')
	->js(NULL, resources_url() . '/libs/jquery-ui/js/jquery-ui.min.js', 'jquery')
	->css(NULL, resources_url() . '/css/jquery-ui.css');

Package::add('dropzone')
	->js(NULL, resources_url() . '/libs/dropzone/js/dropzone.min.js', 'jquery')
	->css('dropzone-basic', resources_url() . '/libs/dropzone/css/basic.min.css')
	->css(NULL, resources_url() . '/libs/dropzone/css/dropzone.min.css');

Package::add('datepicker')
	->js(NULL, resources_url() . '/libs/datetimepicker/js/jquery.datetimepicker.min.js', 'jquery')
	->css(NULL, resources_url() . '/libs/datetimepicker/css/jquery.datetimepicker.css');

Package::add('editable')
	->js(NULL, resources_url() . '/libs/x-editable/js/bootstrap-editable.min.js', ['jquery', 'bootstrap'])
	->css(NULL, resources_url() . '/libs/x-editable/css/bootstrap-editable.css');

Package::add('nestable')
	->js(NULL, resources_url() . '/libs/nestable/js/jquery.nestable.js', 'jquery');

Package::add('steps')
	->js(NULL, resources_url() . '/libs/jquery.steps/js/jquery.steps.min.js', 'jquery');

Package::add('noty')
	->js(NULL, resources_url() . '/libs/noty/js/jquery.noty.packaged.min.js', 'jquery');

Package::add('select2')
	->js(NULL, resources_url() . '/libs/select2/js/select2.min.js', 'jquery')
	->js("select2-{$locale}", resources_url() . "/libs/select2/js/i18n/{$locale}.js", 'select2')
	->css(NULL, resources_url() . '/libs/select2/css/select2.min.css');

Package::add('sortable')
	->js(NULL, resources_url() . '/libs/Sortable/js/Sortable.min.js', 'jquery')
	->js('sortable-binding', resources_url() . '/libs/Sortable/js/jquery.binding.js', 'jquery');

Package::add('validate')
	->js(NULL, resources_url() . '/libs/jquery-validation/js/jquery.validate.min.js', 'jquery')
	->js("validate-{$locale}", resources_url() . "/libs/jquery-validation/js/messages_{$locale}.js", 'validate');

Package::add('moment')
	->js(NULL, resources_url() . '/libs/moment/js/moment.min.js', 'jquery')
	->js("moment-{$locale}", resources_url() . "/libs/moment/js/{$locale}.js", 'moment');

Package::add('ckeditor')
	->js('ckeditor-library', resources_url() . '/libs/ckeditor/ckeditor.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ckeditor.js', 'ckeditor-library')
	->js("ckeditor-{$locale}", resources_url() . "/libs/ckeditor/lang/{$locale}.js", 'validate');

Package::add('ace')
	->js('ace-library', resources_url() . '/libs/ace/src-min/ace.js', 'jquery')
	->js(NULL, backend_resources_url() . '/js/ace.js', 'ace-library');

Package::add('diff')
	->js(NULL, resources_url() . '/libs/jsdiff/js/diff.js');

Package::add('datatables')
	->js(NULL, resources_url() . '/libs/datatables/js/jquery.dataTables.min.js', 'jquery')
	->js('datatables.bootstrap', resources_url() . '/libs/datatables/js/datatables.bootstrap.js', 'datatables')
	->css(NULL, resources_url() . '/libs/datatables/css/datatables.bootstrap.css');

WYSIWYG::add('ace', 'Ace', NULL, NULL, WYSIWYG::code());
WYSIWYG::add('ckeditor', 'CKEditor');