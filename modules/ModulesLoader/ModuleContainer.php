<?php namespace KodiCMS\ModulesLoader;

use CMS;
use Cache;
use Carbon\Carbon;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\ModulesLoader\Contracts\ModuleContainerInterface;

class ModuleContainer implements ModuleContainerInterface, Jsonable, Arrayable
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
	 * @var bool
	 */
	protected $isPublishable = true;

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
	protected $controllerNamespacePrefix = 'Http\\Controllers';

	/**
	 * @param string $moduleName
	 * @param null|string $modulePath
	 * @param null|string $namespace
	 */
	public function __construct($moduleName, $modulePath = null, $namespace = null)
	{
		if (empty($modulePath))
		{
			$modulePath = $this->getDefaultModulePath($moduleName);
		}

		$this->path = normalize_path($modulePath);
		$this->name = $moduleName;

		$this->setNamespace($namespace);
	}

	/**
	 * @param string $moduleName
	 * @return string
	 */
	protected function getDefaultModulePath($moduleName)
	{
		return base_path('modules' . DIRECTORY_SEPARATOR . $moduleName);
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
		return $this->namespace;
	}

	/**
	 * @return string
	 */
	public function getControllerNamespace()
	{
		return $this->getNamespace() . '\\' . $this->controllerNamespacePrefix;
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
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function boot($app)
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
	 * @param \Illuminate\Foundation\Application $app
	 * @return $this
	 */
	public function register($app)
	{
		if (!$this->isRegistered)
		{
			$this->loadSystemRoutes($app['router']);

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

		return [
			$this->getViewsPath() => $this->publishViewPath()
		];
	}

	/**
	 * @return bool
	 */
	public function isPublishable()
	{
		return $this->isPublishable;
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return [
			'path' => $this->getPath(),
			'publishPath' => $this->getPublishPath(),
			'localePath' => $this->getLocalePath(),
			'viewsPath' => $this->getViewsPath(),
			'configPath' => $this->getConfigPath(),
			'routesPath' => $this->getRoutesPath(),
			'assetsPath' => $this->getAssetsPackagesPath(),
			'namespace' => $this->getNamespace(),
			'name' => $this->getName(),
		];
	}

	/**
	 * Convert the object to its JSON representation.
	 *
	 * @param  int  $options
	 * @return string
	 */
	public function toJson($options = 0)
	{
		return json_encode($this->toArray(), $options);
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

		if (is_dir($appPath = $this->publishViewPath()))
		{
			view()->addNamespace($namespace, $appPath);
		}

		view()->addNamespace($namespace, $this->getViewsPath());
	}

	/**
	 * @return string
	 */
	protected function publishViewPath()
	{
		return base_path(normalize_path("/resources/views/modules/{$this->getName()}"));
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
	 * @param Router $router
	 */
	protected function loadSystemRoutes(Router $router)
	{

	}

	/**
	 * @param string|null $namespace
	 */
	protected function setNamespace($namespace = null)
	{
		if (!is_null($namespace))
		{
			$this->namespace = $namespace;
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getName();
	}
}