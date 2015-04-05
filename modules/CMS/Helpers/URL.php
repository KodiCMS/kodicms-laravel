<?php namespace KodiCMS\CMS\Helpers;

use Illuminate\Support\Facades\Route;

class URL
{
	/**
	 *
	 * @param string $uri
	 * @param string $current
	 * @return boolean
	 */
	public static function match($uri, $current = NULL)
	{
		$uri = trim($uri, '/');
		if ($current === NULL) {
			$current = Route::getCurrentRoute()->getUri();
		}
		$current = trim($current, '/');
		if ($current == $uri) {
			return TRUE;
		}
		if (empty($uri)) {
			return FALSE;
		}
		if (strpos($current, $uri) !== FALSE) {
			return TRUE;
		}

		return FALSE;
	}
}