<?php namespace KodiCMS\CMS\Loader;

use CMS;
use Cache;
use Carbon\Carbon;
use Illuminate\Routing\Router;
use KodiCMS\Support\Helpers\File;
use Illuminate\Support\Facades\App;
use KodiCMS\CMS\Contracts\ModuleContainerInterface;

class ModuleContainer implements ModuleContainerInterface
{
	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var bool
	 */
	protected $isRegistered = false;

	/**
	 * @var bool
	 */
	protected $isBooted = false;

	/**
	 * @var string
	 */
	protected $namespace = 'KodiCMS';

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $_controllerNamespacePrefix = 'Http\\Controllers';

	/**
	 * @param string $moduleName
	 * @param null|string $modulePath
	 * @param null|string $namespace
	 */
	public function __construct($moduleName, $modulePath = null, $namespace = null)
	{
		if (empty($modulePath))
		{
			$modulePath = base_path('modules/' . $moduleName);
		}

		$this->path = File::normalizePath($modulePath);
		$this->name = $moduleName;
		if (!is_null($namespace))
		{
			$this->namespace = $namespace;
		}
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getNamespace()
	{
		return $this->namespace . '\\' . $this->getName();
	}

	/**
	 * @return string
	 */
	public function getControllerNamespace()
	{
		return $this->getNamespace() . '\\' . $this->_controllerNamespacePrefix;
	}

	/**
	 * @param strimg|array|null $sub
	 * @return string
	 */
	public function getPath($sub = null)
	{
		if (is_array($sub))
		{
			$sub = implode(DIRECTORY_SEPARATOR, $sub);
		}

		$path = $this->path;
		if (!is_null($sub))
		{
			$path .= DIRECTORY_SEPARATOR . $sub;
		}

		return $path;
	}

	/**
	 * @return string
	 */
	public function getLocalePath()
	{
		return $this->getPath(['resources', 'lang']);
	}

	/**
	 * @return string
	 */
	public function getViewsPath()
	{
		return $this->getPath(['resources', 'views']);
	}

	/**
	 * @return string
	 */
	public function getConfigPath()
	{
		return $this->getPath('config');
	}

	/**
	 * @return string
	 */
	public function getAssetsPackagesPath()
	{
		return $this->getPath(['resources', 'packages.php']);
	}

	/**
	 * @return string
	 */
	public function getRoutesPath()
	{
		return $this->getPath(['Http', 'routes.php']);
	}

	/**
	 * @return string
	 */
	public function getServiceProviderPath()
	{
		return $this->getPath(['Providers', 'ModuleServiceProvider.php']);
	}

	/**
	 * @return $this
	 */
	public function boot()
	{
		if (!$this->isBooted)
		{
			$this->loadViews();
			$this->loadTranslations();
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
		if (!$this->isRegistered)
		{
			$serviceProviderPath = $this->getServiceProviderPath();
			if (is_file($serviceProviderPath))
			{
				App::register($this->getNamespace() . '\Providers\ModuleServiceProvider');
			}

			$this->isRegistered = true;
		}

		return $this;
	}

	/**
	 * @param Router $router
	 */
	public function loadRoutes(Router $router)
	{
		if (!CMS::isInstalled())
		{
			return;
		}

		$this->includeRoutes($router);
	}

	/**
	 * Register a config file namespace.
	 * @return void
	 */
	public function loadConfig()
	{
		if (!CMS::isInstalled())
		{
			return [];
		}

		$path = $this->getConfigPath();

		if (!is_dir($path)) return [];

		$configs = Cache::remember("moduleConfig::{$path}", Carbon::now()->addMinutes(10), function () use ($path)
		{
			$configs = [];
			foreach (new \DirectoryIterator($path) as $file)
			{
				if ($file->isDot() OR strpos($file->getFilename(), '.php') === false) continue;
				$key = $file->getBasename('.php');
				$configs[$key] = array_merge(require $file->getPathname(), app('config')->get($key, []));
			}

			return $configs;
		});

		return $configs;
	}

	/**
	 * @return array
	 */
	public function getPublishPath()
	{
		if (!is_dir($this->getViewsPath())) return [];

		$namespace = strtolower($this->getName());

		return [
			$this->getViewsPath() => base_path("/resources/views/module/{$namespace}")
		];
	}

	/**
	 * @param Router $router
	 */
	protected function includeRoutes(Router $router)
	{
		if (is_file($routesFile = $this->getRoutesPath()))
		{
			$router->group(['namespace' => $this->getControllerNamespace()], function ($router) use ($routesFile)
			{
				require $routesFile;
			});
		}
	}

	protected function loadAssets()
	{
		if (is_file($packagesFile = $this->getAssetsPackagesPath()))
		{
			require $packagesFile;
		}
	}

	/**
	 * Register a view file namespace.
	 *
	 * @return void
	 */
	protected function loadViews()
	{
		$namespace = strtolower($this->getName());

		if (is_dir($appPath = base_path("/resources/views/module/{$namespace}")))
		{
			app('view')->addNamespace($namespace, $appPath);
		}

		app('view')->addNamespace($namespace, $this->getViewsPath());
	}

	/**
	 * Register a translation file namespace.
	 *
	 * @return void
	 */
	protected function loadTranslations()
	{
		$namespace = strtolower($this->getName());
		app('translator')->addNamespace($namespace, $this->getLocalePath());
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getName();
	}
}