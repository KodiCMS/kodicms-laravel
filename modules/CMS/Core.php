<?php namespace KodiCMS\CMS;

class Core {

	const VERSION 	= '14.0.0';
	const NAME		= 'KodiCMS';
	const WEBSITE	= 'http://kodicms.ru';

	/**
	 * @return string
	 */
	public static function backendPath()
	{
		return config('cms.backend_path', 'backend');
	}

	/**
	 * @return string
	 */
	public static function backendResourcesPath()
	{
		return public_path('cms/');
	}

	/**
	 * @return string
	 */
	public static function backendResourcesURL()
	{
		return url('cms');
	}
}