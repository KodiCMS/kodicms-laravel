<?php

PackageManager::add('elfinder')
    ->js('elfinder.lib', resources_url('/libs/elfinder/js/elfinder.min.js'), 'global')
    ->js('elfinder.'.Lang::getLocale(), resources_url('/libs/elfinder/js/i18n/elfinder.'.Lang::getLocale().'.js'), 'elfinder.lib')
    ->css('elfinder.lib', resources_url('/libs/elfinder/css/elfinder.min.css'));
