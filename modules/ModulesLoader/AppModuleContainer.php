<?php namespace KodiCMS\ModulesLoader;

class AppModuleContainer extends ModuleContainer
{
	/**
	 * @var string
	 */
	protected $namespace = '';

	/**
	 * @var bool
	 */
	protected $isPublishable = false;

	/**
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function boot($app)
	{
		if (!$this->isBooted)
		{
			$this->loadAssets();
			$this->isBooted = true;
		}

		return $this;
	}

	/**
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function register($app)
	{
		$this->isRegistered = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRoutesPath()
	{
		return $this->getPath(['app', 'Http', 'routes.php']);
	}

	/**
	 * @return string
	 */
	public function getServiceProviderPath()
	{
		return $this->getPath(['app', 'Providers', 'ModuleServiceProvider.php']);
	}

	/**
	 * Register a config file namespace.
	 * @return void
	 */
	public function loadConfig()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public function getPublishPath()
	{
		return [];
	}
}