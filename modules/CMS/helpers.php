<?php
/**
 * @return string
 */
function cms_installed()
{
    return app('cms')->isInstalled();
}

/**
 * @return string
 */
function backend_url_segment()
{
    return app('cms')->backendUrlSegment();
}

/**
 * @param null|string $path
 *
 * @return string
 */
function backend_url($path = null)
{
    return app('cms')->backendUrl($path);
}

/**
 * @param string|null $path
 *
 * @return string
 */
function resources_url($path = null)
{
    return app('cms')->resourcesUrl($path);
}

/**
 * @param string|null $path
 *
 * @return string
 */
function backend_resources_path($path = null)
{
    return app('cms')->backendResourcesPath($path);
}

/**
 * @param string|null $path
 *
 * @return string
 */
function backend_resources_url($path = null)
{
    return app('cms')->backendResourcesUrl($path);
}

/**
 * @return string
 */
function layouts_path()
{
    return base_path(normalize_path('resources/layouts'));
}

/**
 * @return string
 */
function snippets_path()
{
    return base_path(normalize_path('resources/snippets'));
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
    foreach ($arr1 as $key => $value) {
        if (array_key_exists($key, $arr2)) {
            if (is_array($value)) {
                $recursiveDiff = array_keys_exists_recursive($value, $arr2[$key]);
                if (count($recursiveDiff)) {
                    $outputDiff[$key] = $recursiveDiff;
                }
            }
        } else {
            $outputDiff[$key] = $value;
        }
    }

    return $outputDiff;
}

if (! function_exists('acl_check')) {

    /**
     * @param string|array $action
     *
     * @return bool
     */
    function acl_check($action)
    {
        return true;
    }
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
