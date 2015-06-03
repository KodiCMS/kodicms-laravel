<?php namespace KodiCMS\Support\Helpers;


/**
 * Class Locale
 * TODO: вынести в хелпер-функции? Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Helpers
 */
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