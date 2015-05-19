<?php namespace KodiCMS\CMS\Helpers;

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
	 *
	 * @param string $uri
	 * @param string $current
	 * @return boolean
	 */
	public static function match($uri, $current = null)
	{
		$uri = trim($uri, '/');
		if (is_null($current))
		{
			$current = Request::path();
		}

		$current = trim($current, '/');
		if ($current == $uri)
		{
			return true;
		}

		if (empty($uri))
		{
			return false;
		}

		if (strpos($current, $uri) !== false)
		{
			return true;
		}

		return false;
	}
}