<?php namespace KodiCMS\CMS;

use Closure;
use KodiCMS\Support\Helpers\Profiler;
use Illuminate\Support\ServiceProvider;

class Application extends \Illuminate\Foundation\Application
{
	/**
	 * @var array
	 */
	protected $shutdownCallbacks = [];

	/**
	 * Create a new Illuminate application instance.
	 *
	 * @param  string|null  $basePath
	 * @return void
	 */
	public function __construct($basePath = null)
	{
		parent::__construct($basePath);
		register_shutdown_function([$this, 'shutdownHandler']);
	}

	/**
	 * @return bool
	 */
	public function installed()
	{
		return is_file(base_path(app()->environmentFile()));
	}

	/**
	 * @return string
	 */
	public function backendUrlSegmentName()
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
		return url(backend_url() . DIRECTORY_SEPARATOR .'cms');
	}

	/**
	 * @param Closure $callback
	 */
	public function shutdown(Closure $callback)
	{
		$this->shutdownCallbacks[] = $callback;
	}

	public function shutdownHandler()
	{
		$this['events']->fire('app.shutdown', [$this]);

		foreach($this->shutdownCallbacks as $callback)
		{
			$this->call($callback);
		}
	}

	/**
	 * Register a service provider with the application.
	 *
	 * @param  \Illuminate\Support\ServiceProvider|string  $provider
	 * @param  array  $options
	 * @param  bool   $force
	 * @return \Illuminate\Support\ServiceProvider
	 */
	public function register($provider, $options = [], $force = false)
	{
		$className = is_object($provider) ? get_class($provider) : class_basename($provider);

		$token = Profiler::start('Providers', "$className::register");

		$provider = parent::register($provider, $options, $force);

		Profiler::stop($token);

		return $provider;
	}

	/**
	 * Boot the given service provider.
	 *
	 * @param  \Illuminate\Support\ServiceProvider  $provider
	 * @return void
	 */
	protected function bootProvider(ServiceProvider $provider)
	{
		if (method_exists($provider, 'boot')) {
			$className = is_object($provider) ? get_class($provider) : class_basename($provider);

			$token = Profiler::start('Providers', "$className::boot");

			$return = parent::bootProvider($provider);

			Profiler::stop($token);
			return $return;
		}
	}
}