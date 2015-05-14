<?php

use KodiCMS\CMS\Assets\Package;

Package::add('cron')
	->js(NULL, resources_url() . '/libs/jquery-cron-min.js', 'jquery');