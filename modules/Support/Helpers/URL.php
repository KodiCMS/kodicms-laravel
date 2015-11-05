<?php

namespace KodiCMS\Support\Helpers;

use Request;

/**
 * Class URL
 * TODO: Убрать статику. Greabock 20.05.2015.
 */
class URL
{
    /**
     * @param string $url
     *
     * @return string
     */
    public static function frontend($url)
    {
        $suffix = strlen(rtrim($url, '/')) > 0 ? static::getSuffix() : null;

        $params = ['slug' => $url];
        if (! empty($suffix)) {
            $params['suffix'] = $suffix;
        }

        return route('frontend.url', $params);
    }

    /**
     * @return string
     */
    public static function getSuffix()
    {
        return config('cms.url_suffix', '');
    }

    /**
     * @param string|null $url
     * @param string|null $suffix
     *
     * @return bool
     */
    public static function hasSuffix($url = null, $suffix = null)
    {
        if (is_null($url)) {
            $url = Request::path();
        }

        $ext = pathinfo($url, PATHINFO_EXTENSION);
        if (! empty($ext)) {
            return true;
        }

        if (is_null($suffix)) {
            $suffix = static::getSuffix();
        }

        return ! (strstr($url, $suffix) === false);
    }

    /**
     * @param string|null $url
     *
     * @return bool
     */
    public static function isBackend($url = null)
    {
        return static::startWith(backend_url_segment(), $url);
    }

    /**
     * @param string      $segment
     * @param string|null $url
     *
     * @return bool
     */
    public static function startWith($segment, $url = null)
    {
        if (is_null($url)) {
            $url = Request::path();
        }

        $parsed = parse_url($url);
        $path_parts = explode('/', $parsed['path']);

        return $path_parts[1] == $segment;
    }

    /**
     * @param string      $uri
     * @param string|null $url
     *
     * @return bool
     */
    public static function match($uri, $url = null)
    {
        if (is_null($url)) {
            $url = Request::path();
        }

        $url = trim($url, '/');
        $uri = trim($uri, '/');

        if ($url == $uri) {
            return true;
        }

        if (empty($uri)) {
            return false;
        }

        if (strpos($url, $uri) !== false) {
            return true;
        }

        return false;
    }

    /**
     * Merges the current GET parameters with an array of new or overloaded
     * parameters and returns the resulting query string.
     *
     *     // Returns "?sort=title&limit=10" combined with any existing GET values
     *     $query = URL::query(array('sort' => 'title', 'limit' => 10));
     *
     * Typically you would use this when you are sorting query results,
     * or something similar.
     *
     * [!!] Parameters with a NULL value are left out.
     *
     * @param   array   $params Array of GET parameters
     * @param   bool $useGet Include current request GET parameters
     *
     * @return  string
     */
    public static function query(array $params = null, $useGet = true)
    {
        if ($useGet) {
            if ($params === null) {
                // Use only the current parameters
                $params = $_GET;
            } else {
                // Merge the current and new parameters
                $params = array_merge_recursive($_GET, $params);
            }
        }

        if (empty($params)) {
            // No query parameters
            return '';
        }

        // Note: http_build_query returns an empty string for a params array with only NULL values
        $query = http_build_query($params, '', '&');

        // Don't prepend '?' to an empty string
        return ($query === '') ? '' : ('?'.$query);
    }
}
