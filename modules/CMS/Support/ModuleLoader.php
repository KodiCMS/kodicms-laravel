<?php namespace KodiCMS\CMS\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

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
	protected static $filesChanged = FALSE;

	/**
	 * @param array $modulesList
	 */
	public function __construct(array $modulesList)
	{
		foreach ($modulesList as $moduleName => $modulePath) {
			if (is_numeric($moduleName)) {
				$moduleName = $modulePath;
				$modulePath = NULL;
			}
			$this->addModule($moduleName, $modulePath);
		}
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
	 * @param string $modulePath
	 * @return $this
	 */
	public function addModule($moduleName, $modulePath)
	{
		$this->_registeredModules[] = new Module($moduleName, $modulePath);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function bootModules()
	{
		foreach ($this->_registeredModules as $module) {
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
		foreach ($this->_registeredModules as $module) {
			$module->register();
		}
		return $this;
	}

	/**
	 * TODO Добавить кеширование найденых файлов
	 *
	 * @param   string $dir directory name (views, i18n, classes, extensions, etc.)
	 * @param   string $file filename with subdirectory
	 * @param   string $ext extension to search for
	 * @return  string  single file path
	 */
	public function findFile($dir, $file, $ext = NULL)
	{
		if ($ext === NULL) {
			// Use the default extension
			$ext = '.php';
		} elseif ($ext) {
			// Prefix the extension with a period
			$ext = ".{$ext}";
		} else {
			// Use no extension
			$ext = '';
		}

		// Create a partial path of the filename
		$path = $dir . DIRECTORY_SEPARATOR . $file . $ext;

		if (isset(static::$files[$path])) {
			// This path has been cached
			return static::$files[$path];
		}

		// The file has not been found yet
		$found = FALSE;

		foreach ($this->getRegisteredModules() as $module) {
			$dir = $module->getPath() . DIRECTORY_SEPARATOR;

			if (is_file($dir . $path)) {
				// A path has been found
				$found = $dir . $path;

				// Stop searching
				break;
			}
		}

		// Add the path to the cache
		static::$files[$path] = $found;

		static::$filesChanged = TRUE;

		return $found;
	}

	public function getFoundFilesFromCache()
	{
		static::$files = Cache::get('ModuleLoader::findFile', []);
	}

	public function cacheFoundFiles()
	{
		if (static::$filesChanged) {
			Cache::put('ModuleLoader::findFile', static::$files, Carbon::now()->addMinutes(10));
		}
	}
}