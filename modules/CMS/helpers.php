<?php

use KodiCMS\Support\Helpers\File;

if (!function_exists('backend_url'))
{
	/**
	 * @param null|string $path
	 *
	 * @return string
	 */
	function backend_url($path = null)
	{
		return App::backendUrlSegmentName() . (!is_null($path) ? '/' . ltrim($path, '/') : $path);
	}
}

/**
 * @return string
 */
function resources_url($path = null)
{
	return App::resourcesURL(!is_null($path) ? '/' . ltrim($path, '/') : $path);
}

/**
 * @return string
 */
function backend_resources_url($path = null)
{

	return App::backendResourcesURL(!is_null($path) ? '/' . ltrim($path, '/') : $path);
}

/**
 * @param string $path
 *
 * @return string
 */
function normalize_path($path)
{
	return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
}

/**
 * @return string
 */
function layouts_path()
{
	return normalize_path(base_path('resources/layouts'));
}

/**
 * @return string
 */
function snippets_path()
{
	return normalize_path(base_path('resources/snippets'));
}

/**
 * @param array $arr1
 * @param array $arr2
 *
 * @return array
 */
function array_keys_exists_recursive(array $arr1, array $arr2)
{
	$outputDiff = [];

	foreach ($arr1 as $key => $value)
	{
		if (array_key_exists($key, $arr2))
		{
			if (is_array($value))
			{
				$recursiveDiff = array_keys_exists_recursive($value, $arr2[$key]);

				if (count($recursiveDiff))
				{
					$outputDiff[$key] = $recursiveDiff;
				}
			}
		}
		else
		{
			$outputDiff[$key] = $value;
		}
	}

	return $outputDiff;
}

/**
 * @param $string
 *
 * @return mixed
 */
function __($string)
{
	return $string;
}