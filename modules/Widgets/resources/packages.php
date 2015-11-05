<?php

PackageManager::add('page-wysiwyg')
    ->js('page-wysiwyg-libraries', resources_url('/js/page-wysiwyg-libraries.js'), 'jquery')
    ->js(null, resources_url('/js/page-wysiwyg.js'), ['page-wysiwyg-libraries'])
    ->css(null, resources_url('/css/page-wysiwyg.css'));
