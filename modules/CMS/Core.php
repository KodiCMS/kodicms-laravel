<?php namespace KodiCMS\CMS;

use Closure;
use Illuminate\Container\Container;

/**
 * Class Core
 * TODO: убрать статику. Greabock 20.05.2015
 *
 * @package KodiCMS\CMS
 */
class Core extends Container {

	const VERSION 	= '0.0.1 alpha';
	const NAME		= 'KodiCMS';
	const WEBSITE	= 'http://kodicms.ru';

	/**
	 * @var array
	 */
	protected $shutdowsCalbbacks = [];

	/**
	 * TODO: доработать проверку
	 * @return bool
	 */
	public static function isInstalled()
	{
		return is_file(base_path(app()->environmentFile()));
	}

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
		return public_path('cms' . DIRECTORY_SEPARATOR);
	}

	/**
	 * @return string
	 */
	public static function resourcesURL()
	{
		return url('cms');
	}

	/**
	 * @return string
	 */
	public static function backendResourcesURL()
	{
		return url(static::backendPath() . DIRECTORY_SEPARATOR .'cms');
	}

	public function __construct()
	{
		register_shutdown_function([$this, 'shutdownHandler']);
	}

	public function shutdown(Closure $callback)
	{
		$this->shutdowsCalbbacks[] = $callback;
	}

	public function shutdownHandler()
	{
		foreach($this->shutdowsCalbbacks as $callback)
		{
			$this->call($callback);
		}
	}
}