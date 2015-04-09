<?php namespace KodiCMS\CMS\Model;

use KodiCMS\CMS\Helpers\File as FileSystem;
use SplFileInfo;
use Iterator;

class FileCollection implements Iterator
{
	/**
	 * @var string
	 */
	protected $directory;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var array
	 */
	protected $files = [];

	/**
	 * @var array
	 */
	protected $newFiles = [];

	public function __construct($directory)
	{
		$this->directory = new SplFileInfo($directory);
		$this->settings = $this->getSettingsFile();

		$files = app('files')->files($this->getRealPath());
		foreach ($files as $path) {
			$filename = pathinfo($path, PATHINFO_FILENAME);
			$file = new File($path);
			$file->setSettings(array_get($this->settings, $filename));

			$this->files[$path] = $file;
		}
	}

	/**
	 * @return bool
	 */
	public function isReadOnly()
	{
		return !$this->directory->isWritable();
	}

	/**
	 * @return string
	 */
	public function getRealPath()
	{
		return $this->directory->getRealPath();
	}

	/**
	 * @return SplFileInfo|string
	 */
	public function getDirectory()
	{
		return $this->directory;
	}

	/**
	 * @return string
	 */
	public function getSettingsFilePath()
	{
		return FileSystem::normalizePath(base_path($this->directory . DIRECTORY_SEPARATOR . '.settings.php'));
	}

	/**
	 * @return array
	 */
	public function getSettingsFile()
	{
		$settingsFile = $this->getSettingsFilePath();
		if (is_file($settingsFile)) {
			$seetings = (array)require $settingsFile;
		} else {
			$seetings = [];
		}

		return $seetings;
	}

	/**
	 * @param string $filename
	 * @return bool
	 */
	public function findFile($filename)
	{
		if (strpos($filename, File::$ext) !== FALSE) {
			$method = 'getFilename';
		} else {
			$method = 'getName';
		}

		foreach ($this->files as $file) {
			if ($file->{$method}() == $filename) {
				return $file;
			}
		}

		return FALSE;
	}

	/**
	 * @return File
	 */
	public function newFile()
	{
		return $this->newFiles[] = new File(NULL, $this->getRealPath());
	}

	/**
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * @return $this
	 */
	public function saveChanges()
	{
		foreach($this->files as $file)
		{
			$file->save();
		}

		foreach($this->newFiles as $i => $file)
		{
			if($file->save())
			{
				unset($this->newFiles[$i]);
				$this->files[$file->getRealPath()] = $file;
			}
		}

		$this->saveSettings();

		return $this;
	}

	/**
	 * @return int
	 */
	protected function saveSettings()
	{
		$settings = [];
		foreach ($this->files as $file) {
			$settings[$file->getFilename()] = $file->getSettings();
		}

		$data = "return ";
		$data .= var_export($settings, TRUE);
		$data .= ";";

		return file_put_contents($this->getSettingsFilePath(), $data);
	}

	public function current()
	{
		return current($this->files);
	}

	public function next()
	{
		next($this->files);
	}

	public function key()
	{
		return key($this->files);
	}

	public function valid()
	{
		return isset($this->files[$this->key()]);
	}

	public function rewind()
	{
		reset($this->files);
	}
}