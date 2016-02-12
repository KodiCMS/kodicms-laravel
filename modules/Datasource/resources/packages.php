<?php

$locale = Lang::getLocale();

PackageManager::add('query-builder')
    ->js(null, resources_url('/libs/query-builder/query-builder.js'), 'libraries')
    ->css(null, resources_url("/libs/query-builder/query-builder.css"));