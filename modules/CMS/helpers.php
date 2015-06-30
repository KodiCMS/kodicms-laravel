<?php

use KodiCMS\Support\Helpers\File;

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

/**
 * @param array $arr1
 * @param array $arr2
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