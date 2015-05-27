<?php namespace KodiCMS\CMS\Loader;

class AppModuleContainer extends ModuleContainer
{
	/**
	 * @var string
	 */
	protected $namespace = 'App';

	/**
	 * @return $this
	 */
	public function boot()
	{
		if (!$this->isBooted)
		{
			$this->loadAssets();
			$this->isBooted = true;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function register()
	{
		$this->isRegistered = true;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getRoutesPath()
	{
		return $this->getPath(['App', 'Http', 'routes.php']);
	}

	/**
	 * @return string
	 */
	public function getServiceProviderPath()
	{
		return $this->getPath(['App', 'Providers', 'ModuleServiceProvider.php']);
	}

	/**
	 * Register a config file namespace.
	 * @return void
	 */
	public function loadConfig()
	{
		return [];
	}
}