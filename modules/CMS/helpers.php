<?php

use KodiCMS\CMS\Helpers\File;

/**
 * @return string
 */
function resources_url()
{
	return CMS::resourcesURL();
}

/**
 * @return string
 */
function backend_resources_url()
{
	return CMS::backendResourcesURL();
}

/**
 * @return string
 */
function layouts_path()
{
	return File::normalizePath(base_path('resources/layouts'));
}

/**
 * @return string
 */
function snippets_path()
{
	return File::normalizePath(base_path('resources/snippets'));
}

/**
 * @param string $message
 * @param array $params
 * @return string
 */
function __($message, array $params = [])
{
	return trans($message, $params);
}