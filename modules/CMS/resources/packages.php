<?php

$locale = Lang::getLocale();

PackageManager::add('libraries')
    ->js(null, resources_url('/js/libraries.js'))
    ->js("moment-{$locale}", resources_url("/libs/moment/js/{$locale}.js"), 'libraries');

PackageManager::add('core')
    ->css('global', resources_url('/css/app.css'))
    ->js(null, resources_url('/js/backend.js'), 'libraries');

PackageManager::add('jquery')
    ->js(null, resources_url('/libs/jquery/js/jquery.min.js'));

PackageManager::add('bootstrap')
    ->css(null, resources_url('/libs/bootstrap/css/bootstrap.min.css'))
    ->js(null, resources_url('/libs/bootstrap/js/bootstrap.min.js'), 'jquery');

PackageManager::add('bootstrap-theme')
    ->css(null, resources_url('/libs/bootstrap/css/bootstrap-theme.min.css'), 'bootstrap');

PackageManager::add('underscore')
    ->js(null, resources_url('/libs/underscore/js/underscore-min.js'), 'jquery');

PackageManager::add('backbone')
    ->js(null, resources_url('/libs/backbone/js/backbone-min.js'), 'underscore');

PackageManager::add('jquery-ui')
    ->js(null, resources_url('/libs/jquery-ui/js/jquery-ui.min.js'), 'jquery')
    ->css(null, resources_url('/css/jquery-ui.css'));

PackageManager::add('fontawesome')
       ->css(null, resources_url('/libs/font-awesome/css/font-awesome.min.css'));

PackageManager::add('jquery-tagsinput')
    ->js(null, resources_url('/libs/jquery.tagsinput/js/jquery.tagsinput.js'), ['jquery', 'jquery-ui'])
    ->css(null, resources_url('/libs/jquery.tagsinput//css/jquery.tagsinput.css'));

PackageManager::add('dropzone')
    ->js(null, resources_url('/libs/dropzone/js/dropzone.min.js'), ['jquery', 'libraries'])
    ->css('dropzone-basic', resources_url('/libs/dropzone/css/basic.min.css'))
    ->css(null, resources_url('/libs/dropzone/css/dropzone.min.css'));

PackageManager::add('datepicker')
    ->js(null, resources_url('/libs/datetimepicker/js/jquery.datetimepicker.min.js'), ['jquery', 'libraries'])
    ->css(null, resources_url('/libs/datetimepicker/css/jquery.datetimepicker.css'));

PackageManager::add('editable')
    ->js(null, resources_url('/libs/x-editable/js/bootstrap-editable.min.js'), ['jquery', 'bootstrap'])
    ->css(null, resources_url('/libs/x-editable/css/bootstrap-editable.css'));

PackageManager::add('nestable')
    ->js(null, resources_url('/libs/nestable/js/jquery.nestable.js'), ['jquery', 'libraries']);

PackageManager::add('steps')
    ->js(null, resources_url('/libs/jquery.steps/js/jquery.steps.min.js'), ['jquery', 'libraries']);

PackageManager::add('noty')
    ->js(null, resources_url('/libs/noty/js/jquery.noty.packaged.min.js'), ['jquery', 'libraries']);

PackageManager::add('select2')
    ->js(null, resources_url('/libs/select2/js/select2.min.js'), ['jquery', 'libraries'])
    ->js("select2-{$locale}", resources_url("/libs/select2/js/i18n/{$locale}.js"), 'select2')
    ->css(null, resources_url('/libs/select2/css/select2.min.css'));

PackageManager::add('sortable')
    ->js(null, resources_url('/libs/Sortable/js/Sortable.min.js'), ['jquery', 'libraries'])
    ->js('sortable-binding', resources_url('/libs/Sortable/js/jquery.binding.js'), ['jquery', 'libraries']);

PackageManager::add('validate')
    ->js(null, resources_url('/libs/jquery-validation/js/jquery.validate.min.js'), ['jquery', 'libraries'])
    ->js("validate-{$locale}", resources_url("/libs/jquery-validation/js/messages_{$locale}.js"), 'validate');

PackageManager::add('moment')
    ->js(null, resources_url('/libs/moment/js/moment.min.js'), ['jquery', 'libraries'])
    ->js("moment-{$locale}", resources_url("/libs/moment/js/{$locale}.js"), 'moment');

PackageManager::add('ckeditor')
    ->js('ckeditor-library', resources_url('/libs/ckeditor/ckeditor.js'), ['jquery', 'libraries'])
    ->js(null, backend_resources_url('/js/ckeditor.js'), 'ckeditor-library')
    ->js("ckeditor-{$locale}", resources_url("/libs/ckeditor/lang/{$locale}.js"), 'validate');

PackageManager::add('ace')
    ->js('ace-library', resources_url('/libs/ace/src-min/ace.js'), ['jquery', 'libraries'])
    ->js(null, backend_resources_url('/js/ace.js'), 'ace-library');

PackageManager::add('diff')
    ->js(null, resources_url('/libs/jsdiff/js/diff.js'));

PackageManager::add('datatables')
    ->js(null, resources_url('/libs/datatables/js/jquery.dataTables.min.js'), ['jquery', 'libraries'])
    ->js('datatables.bootstrap', resources_url('/libs/datatables/js/datatables.bootstrap.js'), 'datatables')
    ->css(null, resources_url('/libs/datatables/css/datatables.bootstrap.css'));

WYSIWYG::add('ace', 'Ace', null, null, WYSIWYG::code());
WYSIWYG::add('ckeditor', 'CKEditor');
