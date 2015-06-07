<?php namespace KodiCMS\CMS\Loader;

use Carbon\Carbon;
use KodiCMS\Support\Helpers\File;
use Illuminate\Support\Facades\Cache;
use KodiCMS\CMS\Exceptions\ModuleLoaderException;
use KodiCMS\CMS\Contracts\ModuleContainerInterface;

class ModuleLoader
{
	/**
	 * @var array
	 */
	protected $registeredModules = [];

	/**
	 * @var  array   File path cache, used when caching is true
	 */
	protected $files = [];

	/**
	 * @var bool
	 */
	protected $filesChanged = false;

	/**
	 * @param array $modulesList
	 */
	public function __construct(array $modulesList)
	{
		foreach ($modulesList as $moduleName => $modulePath)
		{
			$moduleNamespace = null;

			if (is_array($modulePath))
			{
				$moduleNamespace = array_get($modulePath, 'namespace');
				$modulePath = array_get($modulePath, 'path');
			}
			else if (is_numeric($moduleName))
			{
				$moduleName = $modulePath;
				$modulePath = null;
			}

			if (is_null($modulePath))
			{
				$modulePath = base_path('modules/' . $moduleName);
			}

			$this->addModule($moduleName, $modulePath, $moduleNamespace);
		}

		$this->addModule('App', base_path(), '');
	}

	/**
	 * @return array
	 */
	public function getRegisteredModules()
	{
		return $this->registeredModules;
	}

	/**
	 * @param string $moduleName
	 * @param string|null $modulePath
	 * @param string|null $namespace
	 * @param string|null $moduleContainerClass
	 * @return $this
	 */
	public function addModule($moduleName, $modulePath = null, $namespace = null, $moduleContainerClass = null)
	{
		if (is_null($namespace))
		{
			$namespace = 'KodiCMS\\' . $moduleName;
		}

		$namespace = trim($namespace, '\\');

		if (is_null($moduleContainerClass))
		{
			$moduleContainerClass = '\\' . $namespace . '\\ModuleContainer';
		}

		$defaultModuleClass = '\\KodiCMS\\CMS\\Loader\\' . $moduleName . 'ModuleContainer';

		if (!class_exists($moduleContainerClass))
		{
			$moduleContainerClass = class_exists($defaultModuleClass)
				? $defaultModuleClass
				: '\\KodiCMS\\CMS\\Loader\\ModuleContainer';
		}

		$moduleContainer = new $moduleContainerClass($moduleName, $modulePath, $namespace);

		$this->registerModule($moduleContainer);

		return $this;
	}

	/**
	 * @param ModuleContainerInterface $module
	 */
	public function registerModule(ModuleContainerInterface $module)
	{
		$this->registeredModules[] = $module;
	}

	/**
	 * @return $this
	 */
	public function bootModules()
	{
		foreach ($this->getRegisteredModules() as $module)
		{
			$module->boot();
		}

		$this->getFoundFilesFromCache();

		return $this;
	}

	/**
	 * @return $this
	 */
	public function registerModules()
	{
		foreach ($this->getRegisteredModules() as $module)
		{
			$module->register();
		}

		return $this;
	}

	/**
	 * @param string|array|null $sub
	 * @return array
	 */
	public function getPaths($sub = null)
	{
		$paths = [];

		foreach ($this->getRegisteredModules() as $module)
		{
			if (is_dir($dir = $module->getPath($sub)))
			{
				// This path has a file, add it to the list
				$paths[] = $dir;
			}
		}

		return $paths;
	}

	/**
	 * @param   string $dir directory name (views, i18n, classes, extensions, etc.)
	 * @param   string $file filename with subdirectory
	 * @param   string $ext extension to search for
	 * @param   boolean $array return an array of files?
	 * @return  array   a list of files when $array is TRUE
	 * @return  string  single file path
	 */
	public function findFile($dir, $file, $ext = null, $array = false)
	{
		if ($ext === null)
		{
			// Use the default extension
			$ext = '.php';
		}
		elseif ($ext)
		{
			// Prefix the extension with a period
			$ext = ".{$ext}";
		}
		else
		{
			// Use no extension
			$ext = '';
		}

		// Create a partial path of the filename
		$path = File::normalizePath($dir . DIRECTORY_SEPARATOR . $file . $ext);

		if (isset($this->files[$path . ($array ? '_array' : '_path')]))
		{
			// This path has been cached
			return $this->files[$path . ($array ? '_array' : '_path')];
		}

		if ($array)
		{
			// Array of files that have been found
			$found = [];

			foreach ($this->getRegisteredModules() as $module)
			{
				$dir = $module->getPath() . DIRECTORY_SEPARATOR;

				if (is_file($dir . $path))
				{
					// This path has a file, add it to the list
					$found[] = $dir . $path;
				}
			}
		}
		else
		{
			// The file has not been found yet
			$found = false;

			foreach ($this->getRegisteredModules() as $module)
			{
				$dir = $module->getPath() . DIRECTORY_SEPARATOR;

				if (is_file($dir . $path))
				{
					// A path has been found
					$found = $dir . $path;

					// Stop searching
					break;
				}
			}
		}

		// Add the path to the cache
		$this->files[$path . ($array ? '_array' : '_path')] = $found;

		// Files have been changed
		$this->filesChanged = true;

		return $found;
	}

	public function getFoundFilesFromCache()
	{
		$this->files = Cache::get('ModuleLoader::findFile', []);
	}

	public function cacheFoundFiles()
	{
		if ($this->filesChanged)
		{
			Cache::put('ModuleLoader::findFile', $this->files, Carbon::now()->addMinutes(10));
		}
	}
}