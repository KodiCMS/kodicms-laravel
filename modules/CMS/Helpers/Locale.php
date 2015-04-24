<?php namespace KodiCMS\CMS\Helpers;

class Locale
{
	/**
	 * @return array
	 */
	public static function getAvailable()
	{
		return config('cms.locales', []);
	}
}