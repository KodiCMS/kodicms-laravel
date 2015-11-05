<?php

/********************************************
 * ColumnFilters
 ********************************************/
PackageManager::add(KodiCMS\SleepingOwlAdmin\ColumnFilters\Select::class)
    ->js(null, resources_url('sleepingowl/default/js/columnfilters/select.js'));

PackageManager::add(KodiCMS\SleepingOwlAdmin\ColumnFilters\Date::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/datetime/init.js'), 'datepicker');

PackageManager::add(KodiCMS\SleepingOwlAdmin\ColumnFilters\Range::class)
    ->js(null, resources_url('sleepingowl/default/js/columnfilters/range.js'));

PackageManager::add(KodiCMS\SleepingOwlAdmin\ColumnFilters\Text::class)
    ->js(null, resources_url('sleepingowl/default/js/columnfilters/text.js'));

/********************************************
 * FormItems
 ********************************************/
PackageManager::add(KodiCMS\SleepingOwlAdmin\FormItems\CKEditor::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/ckeditor/ckeditor.js'), 'ckeditor');

//AssetManager::addScript('admin::default/js/formitems/image/init.js');
//AssetManager::addScript('admin::default/js/formitems/image/flow.min.js');
PackageManager::add(KodiCMS\SleepingOwlAdmin\FormItems\Image::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/image/init.js'));

//AssetManager::addStyle('admin::default/css/formitems/select/chosen.css');
//AssetManager::addScript('admin::default/js/formitems/select/chosen.jquery.min.js');
//AssetManager::addScript('admin::default/js/formitems/select/init.js');
PackageManager::add(KodiCMS\SleepingOwlAdmin\FormItems\Select::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/select/init.js'));

//AssetManager::addScript('admin::default/js/formitems/image/initMultiple.js');
//AssetManager::addScript('admin::default/js/formitems/image/flow.min.js');
//AssetManager::addScript('admin::default/js/formitems/image/Sortable.min.js');
//AssetManager::addScript('admin::default/js/formitems/image/sortable.jquery.binding.js');
//AssetManager::addStyle('admin::default/css/formitems/image/images.css');
PackageManager::add(KodiCMS\SleepingOwlAdmin\FormItems\Images::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/image/initMultiple.js'))
    ->css(null, resources_url('sleepingowl/default/css/formitems/image/images.css'));

//AssetManager::addStyle('admin::default/css/formitems/datetime/bootstrap-datetimepicker.min.css');
//AssetManager::addStyle('admin::default/css/formitems/datetime/style.css');
//AssetManager::addScript('admin::default/js/formitems/datetime/moment-with-locales.min.js');
//AssetManager::addScript('admin::default/js/formitems/datetime/s_bootstrap-datetimepicker.min.js');
//AssetManager::addScript('admin::default/js/formitems/datetime/init.js');
PackageManager::add(KodiCMS\SleepingOwlAdmin\FormItems\BaseDateTime::class)
    ->js(null, resources_url('sleepingowl/default/js/formitems/datetime/init.js'), 'datepicker');

/********************************************
 * Columns
 ********************************************/
PackageManager::add(KodiCMS\SleepingOwlAdmin\Columns\Column\Checkbox::class)
    ->js(null, resources_url('sleepingowl/default/columns/checkbox.js'));

//AssetManager::addScript('admin::default/js/bootbox.js');
//AssetManager::addScript('admin::default/js/columns/control.js');
PackageManager::add(KodiCMS\SleepingOwlAdmin\Columns\Column\Control::class)
    ->js(null, resources_url('sleepingowl/default/columns/control.js'));

//AssetManager::addStyle('admin::default/css/ekko-lightbox.min.css');
//AssetManager::addScript('admin::default/js/ekko-lightbox.min.js');
//AssetManager::addScript('admin::default/js/columns/image.js');
PackageManager::add(KodiCMS\SleepingOwlAdmin\Columns\Column\Image::class)
    ->js(null, resources_url('sleepingowl/default/columns/image.js'));

/********************************************
 * Display
 ********************************************/
//addScript('admin::default/js/jquery.nestable.js');
//addScript('admin::default/js/nestable.js');
//addStyle('admin::default/css/jquery.nestable.css');
PackageManager::add(KodiCMS\SleepingOwlAdmin\Display\DisplayTree::class)
    ->js(null, resources_url('sleepingowl/default/js/nestable.js'), 'nestable');

//AssetManager::addScript('admin::default/js/datatables/jquery.dataTables.min.js');
//AssetManager::addScript('admin::default/js/datatables/jquery.dataTables_bootstrap.js');
//AssetManager::addScript('admin::default/js/notify-combined.min.js');
//AssetManager::addScript('admin::default/js/datatables/init.js');
//AssetManager::addStyle('admin::default/css/dataTables.bootstrap.css');
PackageManager::add(KodiCMS\SleepingOwlAdmin\Display\DisplayDatatables::class)
    ->js(null, resources_url('sleepingowl/default/js/datatables/init.js'));
