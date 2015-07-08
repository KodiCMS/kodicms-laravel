<?php namespace KodiCMS\CMS;

use Closure;
use Illuminate\Container\Container;

/**
 * Class Core
 * @package KodiCMS\CMS
*/
class Core extends Container {

	const VERSION 	= '0.0.1 alpha';

	const NAME		= 'KodiCMS';

	const WEBSITE	= 'http://kodicms.ru';

	/**
	 * @var array
	 */
	protected $shutdownCalbbacks = [];

	/**
	 * @return bool
	 */
	public function isInstalled()
	{
		return is_file(base_path(app()->environmentFile()));
	}

	/**
	 * @return string
	 */
	public function backendPath()
	{
		return config('cms.backend_path', 'backend');
	}

	/**
	 * @return string
	 */
	public function backendResourcesPath()
	{
		return public_path('cms' . DIRECTORY_SEPARATOR);
	}

	/**
	 * @return string
	 */
	public function resourcesURL()
	{
		return url('cms');
	}

	/**
	 * @return string
	 */
	public function backendResourcesURL()
	{
		return url(static::backendPath() . DIRECTORY_SEPARATOR .'cms');
	}

	public function __construct()
	{
		register_shutdown_function([$this, 'shutdownHandler']);
	}

	public function shutdown(Closure $callback)
	{
		$this->shutdownCalbbacks[] = $callback;
	}

	public function shutdownHandler()
	{
		foreach($this->shutdownCalbbacks as $callback)
		{
			$this->call($callback);
		}
	}
}