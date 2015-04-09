<?php

use KodiCMS\CMS\Helpers\File;

function resources_url()
{
	return CMS::resourcesURL();
}

function layouts_path()
{
	return File::normalizePath(base_path('resources/layouts'));
}

function snippets_path()
{
	return File::normalizePath(base_path('resources/snippets'));
}

function __($message, array $params = [])
{
	return trans($message, $params);
}