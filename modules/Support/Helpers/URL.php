<?php namespace KodiCMS\Support\Helpers;

use Request;

/**
 * Class URL
 * TODO: Убрать статику. Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Helpers
 */
class URL
{
	/**
	 * @param string $url
	 * @return string
	 */
	public static function frontend($url)
	{
		$suffix = strlen(rtrim($url, '/')) > 0 ? static::getSuffix() : null;

		$params = ['slug' => $url];
		if (!empty($suffix))
		{
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
	 * @return bool
	 */
	public static function hasSuffix($url = null, $suffix = null)
	{
		if (is_null($url))
		{
			$url = Request::path();
		}

		$ext = pathinfo($url, PATHINFO_EXTENSION);
		if (!empty($ext))
		{
			return true;
		}

		if (is_null($suffix))
		{
			$suffix = static::getSuffix();
		}

		return !(strstr($url, $suffix) === false);
	}

	/**
	 * @param string|null $url
	 * @return bool
	 */
	public static function isBackend($url = null)
	{
		return static::startWith(backend_url(), $url);
	}

	/**
	 * @param string $segment
	 * @param string|null $url
	 * @return boolean
	 */
	public static function startWith($segment, $url = null)
	{
		if (is_null($url))
		{
			$url = Request::path();
		}

		$parsed = parse_url($url);
		$path_parts = explode('/', $parsed['path']);
		return $path_parts[1] == $segment;
	}

	/**
	 * @param string $uri
	 * @param string|null $url
	 * @return boolean
	 */
	public static function match($uri, $url = null)
	{
		if (is_null($url))
		{
			$url = Request::path();
		}

		$url = trim($url, '/');
		$uri = trim($uri, '/');

		if ($url == $uri)
		{
			return true;
		}

		if (empty($uri))
		{
			return false;
		}

		if (strpos($url, $uri) !== false)
		{
			return true;
		}

		return false;
	}
}