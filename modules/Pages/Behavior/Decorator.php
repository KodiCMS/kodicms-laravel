<?php namespace KodiCMS\Pages\Behavior;

use KodiCMS\CMS\Helpers\Callback;
use KodiCMS\Pages\Contracts\BehaviorInterface;
use KodiCMS\Pages\Exceptions\BehaviorException;
use KodiCMS\Pages\Model\FrontendPage;

abstract class Decorator implements BehaviorInterface
{
	/**
	 * @var Router
	 */
	protected $router;

	/**
	 * @var FrontendPage
	 */
	protected $page = null;

	/**
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var bool
	 */
	protected $settingsLoaded = false;

	/**
	 * @param array $parameters
	 */
	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;

		$routes = $this->routeList();
		if (isset($parameters['routes']) AND is_array($parameters['routes']))
		{
			$routes = $parameters['routes'] + $routes;
		}

		$this->router = new Router($routes);
	}

	/**
	 * @param FrontendPage $page
	 * @throws BehaviorException
	 */
	public function setPage(FrontendPage &$page)
	{
		if (!is_null($this->page))
		{
			throw new BehaviorException('You can\'t change behavior page');
		}

		$this->page = &$page;
	}

	/**
	 * @return FrontendPage
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @return array
	 */
	public function routeList()
	{
		return [];
	}

	/**
	 * @return Router
	 */
	public function getRouter()
	{
		return $this->router;
	}

	/**
	 * @param string $uri
	 * @return string
	 */
	public function executeRoute($uri)
	{
		$method = $this->getRouter()->findRouteByUri($uri);

		if (strpos($method, '::') !== false)
		{
			Callback::invoke($method, [$this]);
		}
		else
		{
			$this->{$method}();
		}

		return $method;
	}

	/**
	 * @return Settings
	 */
	public function getSettings()
	{
		if (!$this->settingsLoaded)
		{
			$this->settings = new Settings($this->getPage());
			$this->settingsLoaded = true;
		}

		return $this->settings;
	}

	abstract public function execute();
}