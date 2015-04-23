<?php namespace KodiCMS\CMS\Loader;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use KodiCMS\CMS\Helpers\File;

class ModuleLoader
{

	/**
	 * @var array
	 */
	protected $_registeredModules = [];

	/**
	 * @var  array   File path cache, used when caching is true
	 */
	protected static $files = [];

	/**
	 * @var bool
	 */
	protected static $filesChanged = false;

	/**
	 * @param array $modulesList
	 */
	public function __construct(array $modulesList)
	{
		foreach ($modulesList as $moduleName => $modulePath)
		{
			if (is_numeric($moduleName))
			{
				$moduleName = $modulePath;
				$modulePath = null;
			}
			$this->addModule($moduleName, $modulePath);
		}

		$this->addModule('App', base_path());
	}

	/**
	 * @return array
	 */
	public function getRegisteredModules()
	{
		return $this->_registeredModules;
	}

	/**
	 * @param string $moduleName
	 * @param string|null $modulePath
	 * @param string|null $namespace
	 * @return $this
	 */
	public function addModule($moduleName, $modulePath = null, $namespace = null)
	{
		$class = '\\KodiCMS\\' . $moduleName . '\\ModuleContainer';
		$moduleClass = '\\KodiCMS\\CMS\\Loader\\' . $moduleName . 'ModuleContainer';
		if (!class_exists($class))
		{
			$class = class_exists($moduleClass) ? $moduleClass : '\\KodiCMS\\CMS\\Loader\\ModuleContainer';

		}
		$this->_registeredModules[] = new $class($moduleName, $modulePath, $namespace);

		return $this;
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

		if (isset(static::$files[$path . ($array ? '_array' : '_path')]))
		{
			// This path has been cached
			return static::$files[$path . ($array ? '_array' : '_path')];
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
		static::$files[$path . ($array ? '_array' : '_path')] = $found;

		// Files have been changed
		static::$filesChanged = true;

		return $found;
	}

	public function getFoundFilesFromCache()
	{
		static::$files = Cache::get('ModuleLoader::findFile', []);
	}

	public function cacheFoundFiles()
	{
		if (static::$filesChanged)
		{
			Cache::put('ModuleLoader::findFile', static::$files, Carbon::now()->addMinutes(10));
		}
	}
}