<?php namespace KodiCMS\API;

use Route;
use Illuminate\Routing\Route as Router;

class RouteAPI
{
	/**
	 * Register a new GET route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function get($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::get($uri, $action));
	}

	/**
	 * Register a new POST route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function post($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::post($uri, $action));
	}

	/**
	 * Register a new PUT route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function put($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::put($uri, $action));
	}

	/**
	 * Register a new PATCH route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function patch($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::patch($uri, $action));
	}

	/**
	 * Register a new DELETE route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function delete($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::delete($uri, $action));
	}

	/**
	 * Register a new OPTIONS route with the router.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function options($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return $this->addResponseType(Route::options($uri, $action));
	}

	/**
	 * Register a new route responding to all verbs.
	 *
	 * @param  string  $uri
	 * @param  \Closure|array|string  $action
	 * @return \Illuminate\Routing\Route
	 */
	public function any($uri, $action)
	{
		$uri = $this->buildUri($uri);
		return Route::any($uri, $action);
	}

	/**
	 * @param Router $route
	 * @return $this
	 */
	protected function addResponseType(Router $route)
	{
		return $route->where('type', '\.[a-z]+');
	}

	/**
	 * @param string $uri
	 * @return string
	 */
	protected function buildUri($uri)
	{
		if (strpos($uri, 'api.') === false)
		{
			$uri = 'api.' . $uri;
		}

		return $uri . '{type?}';
	}
}