<?php namespace KodiCMS\CMS\Support;

use Illuminate\Support\Facades\App;

class Module
{
	/**
	 * @var string
	 */
	protected $_path;

	/**
	 * @var string
	 */
	protected $_name;

	/**
	 * @var bool
	 */
	protected $_isRegistered = FALSE;

	/**
	 * @var bool
	 */
	protected $_isBooted = FALSE;

	/**
	 * @var string
	 */
	protected $_namespace = 'KodiCMS';

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $_controllerNamespacePrefix = 'Http\\Controllers';

	/**
	 * @param $moduleName
	 * @param null|string $modulePath
	 */
	public function __construct($moduleName, $modulePath = NULL)
	{
		if (empty($modulePath)) {
			$modulePath = implode(DIRECTORY_SEPARATOR, [base_path(), 'modules', $moduleName]);
		}

		$this->_path = $modulePath;
		$this->_name = $moduleName;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->_namespace . '\\' . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getControllerNamespace()
	{
		return $this->getNamespace() . '\\' . $this->_controllerNamespacePrefix;
	}

	/**
	 * @param strimg|null $sub
	 * @return string
	 */
	public function getPath($sub = NULL)
	{
		$path = $this->_path;
		if (is_array($sub)) {
			$sub = implode(DIRECTORY_SEPARATOR, $sub);
		}

		if (!is_null($sub)) {
			$path .= DIRECTORY_SEPARATOR . $sub;
		}

		return $path;
	}

	/**
	 * @return $this
	 */
	public function boot()
	{
		if (!$this->_isBooted) {

			$this->loadRoutes();
			$this->loadViews();
			$this->loadTranslations();
			$this->loadConfig();

			$this->_isBooted = TRUE;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function register()
	{
		if (!$this->_isRegistered) {

			if(strtolower($this->getName()) != 'cms') {
				/*
				 * Register module provider
				 */
				App::register($this->getNamespace() . '\ServiceProvider');
			}

			$this->_isRegistered = TRUE;
		}

		return $this;
	}

	/**
	 * Register a routes file namespace.
	 *
	 * @param bool $wrapNamespace
	 */
	protected function loadRoutes($wrapNamespace = TRUE)
	{
		/*
		 * Add routes, if available
		 */
		$routesFile = $this->getPath('routes.php');

		if (is_file($routesFile)) {
			if($wrapNamespace !== FALSE) {
				App::make('router')->group(['namespace' => $this->getControllerNamespace()], function ($router) use ($routesFile) {
					require $routesFile;
				});
			} else {
				require $routesFile;
			}
		}
	}

	/**
	 * Register a view file namespace.
	 *
	 * @return void
	 */
	protected function loadViews()
	{
		if (is_dir($appPath = base_path() . '/resources/views/vendor/' . $this->getName())) {
			App::make('view')->addNamespace($this->getName(), $appPath);
		}

		App::make('view')->addNamespace($this->getName(), $this->getPath(['resources', 'views']));
	}

	/**
	 * Register a translation file namespace.
	 *
	 * @return void
	 */
	protected function loadTranslations()
	{
		App::make('translator')->addNamespace($this->getName(), $this->getPath(['resources', 'lang']));
	}

	/**
	 * Register a config file namespace.
	 *
	 * @return void
	 */
	protected function loadConfig()
	{
//		\App::make('config')->getLoader()->addNamespace($this->getNamespace(), $this->getPath(['resources', 'lang']));
	}
}