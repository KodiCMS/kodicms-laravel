<?php namespace KodiCMS\Support\Helpers;


/**
 * Class Locale
 * TODO: вынести в хелпер-функции? Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Helpers
 */
class Locale
{
	const DEFAULT_LOCALE = 'sys';

	/**
	 * @return array
	 */
	public static function getAvailable()
	{
		return config('cms.locales', []);
	}

	/**
	 * @return string
	 */
	public static function getSystemDefault()
	{
		return config('app.locale', 'ru');
	}
}