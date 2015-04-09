<?php namespace KodiCMS\CMS\Model;

use Carbon\Carbon;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\CMS\Helpers\File as FileSystem;
use KodiCMS\CMS\Helpers\Text;
use SplFileInfo;
use SplFileObject;
use SplTempFileObject;
use Validator;

class File
{
	public static $ext = '.php';

	/**
	 * @var SplFileObject
	 */
	protected $file;

	/**
	 * @var bool
	 */
	protected $isChanged = FALSE;

	/**
	 * @var array
	 */
	protected $changed = [];

	/**
	 * @var bool
	 */
	protected $readOnly = FALSE;

	/**
	 * @var
	 */
	protected $basePath;

	/**
	 * @var array
	 */
	protected $attributes = [
		'roles' => ['administrator', 'developer'],
		'editor' => 'ace'
	];

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * TODO добавить поддержку модулей
	 * @param SplFileObject|string $filename
	 * @throws Exception
	 */
	public function __construct($filename = NULL, $basePath = NULL)
	{
		if (!is_null($basePath)) {
			$this->basePath = $basePath;
		}

		if ($filename instanceof SplFileObject) {
			$this->file = $filename;
		} else if ($filename instanceof SplFileInfo) {
			$this->file = new SplFileObject($file->getRealPath());
		} else if (!is_null($filename)) {
			if (strpos($filename, File::$ext) === FALSE) {
				$filename .= File::$ext;
			}

			if (is_file($filename)) {
				$this->file = new SplFileObject($filename);
			} else {
				$this->file = new SplTempFileObject();
				$this->setName($filename);
			}
		} else {
			$this->file = new SplTempFileObject();
		}
	}

	/**
	 * @return SplFileObject
	 */
	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @return string
	 */
	public function getExt()
	{
		return $this->isNew() ? static::$ext : $this->file->getExtension();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $filename = str_replace(File::$ext, '', $this->file->getFilename());;
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		return $this->file->getFilename();
	}

	/**
	 * @return string
	 */
	public function getRealPath()
	{
		return $this->file->getRealPath();
	}

	/**
	 * @return string
	 */
	public function getRelativePath()
	{
		return str_replace(base_path(), '', $this->isNew() ? $this->getPath() : $this->getRealPath());
	}

	/**
	 * @return string
	 */
	public function getPath()
	{
		return $this->isNew() ? $this->basePath : $this->file->getPath();
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return Text::bytes($this->file->getSize());
	}

	/**
	 * @return null|string
	 */
	public function getContent()
	{
		if ($this->isNew()) {
			return NULL;
		}

		return file_get_contents($this->getRealPath());
	}

	/**
	 * @return string
	 */
	public function getEditor()
	{
		return array_get($this->attributes, 'editor', config('cms.wysiwyg.default_code_editor'));
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		return (array)array_get($this->attributes, 'roles', []);
	}

	/**
	 * @return array
	 */
	public function getSettings()
	{
		return $this->attributes;
	}

	/**
	 * @return int
	 */
	public function getMTime()
	{
		return Carbon::createFromTimestamp($this->file->getMTime());
	}

	/**
	 * @return bool
	 */
	public function isNew()
	{
		return $this->file instanceof SplTempFileObject;
	}

	/**
	 * @return bool
	 */
	public function isReadOnly()
	{
		return (bool)$this->readOnly || !$this->file->isWritable();
	}

	/**
	 * @param null|string $key
	 * @return bool
	 */
	public function isChanged($key = NULL)
	{
		return is_null($key) ? !empty($this->changed) : array_key_exists($key, $this->changed);
	}

	/**
	 * @return void
	 */
	public function setReadOnly()
	{
		$this->isReadOnly = TRUE;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function setEditor($name)
	{
		return $name;
	}

	/**
	 * @param array $roles
	 * @return array
	 */
	public function setRoles(array $roles)
	{
		return $roles;
	}

	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->changed['name'] = $this->filterName($name);

		return $this;
	}

	/**
	 * @param string $content
	 * @return $this
	 */
	public function setContent($content)
	{
		$this->changed['content'] = $content;

		return $this;
	}

	/**
	 * @param array|null $settings
	 * @return void
	 */
	public function setSettings(array $settings = NULL)
	{
		if (!is_null($settings)) {
			$this->attributes = $settings;
		}
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		return unlink($this->file);
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws Exception
	 */
	public function save(array $data = [])
	{
		if ($this->isReadOnly() AND !$this->isNew()) {
			return FALSE;
		}

		foreach ($data as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method)) {
				$this->{$method}($value);
			} else {
				$this->changed[$key] = $value;
			}
		}

		if (isset($this->changed['name'])) {
			$filename = $this->changed['name'];
		} else {
			$filename = $this->getFilename();
		}

		$validator = Validator::make([
			'name' => $filename
		], [
			'name' => 'required'
		]);

		if ($validator->fails()) {
			throw new Exception;
		}

		$status = FALSE;

		if ($this->isChanged('name') AND $this->getFilename() != $this->changed['name']) {
			if ($this->isNew()) {
				$newFilename = FileSystem::normalizePath(base_path($this->basePath . DIRECTORY_SEPARATOR . $this->changed['name']));
				$status = app('files')->put($newFilename, '') !== FALSE;

			} else {
				$newFilename = FileSystem::normalizePath($this->getPath() . '/' . $this->changed['name']);
				$status = @app('files')->move($this->getRealPath(), $newFilename);
			}

			if ($status) {
				$this->file = new SplFileObject($newFilename);
			}
		}

		if ($status AND $this->isChanged('content')) {
			$status = app('files')->put($this->getRealPath(), $this->changed['content']) !== FALSE;
		}

		$this->changed = [];

		return $status;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getContent();
	}

	/**
	 *
	 * @param string $filename
	 * @return string
	 */
	public function filterName($filename)
	{
		$filename = str_replace(File::$ext, '', $filename);
		$filename = preg_replace('/[^a-zA-Z0-9\-\_]/', '-', strtolower($filename));
		foreach (['-', '_', '\.'] as $separator) {
			$filename = preg_replace('/' . $separator . '+/', trim($separator, '\\'), $filename);
		}

		if (strpos($filename, File::$ext) === FALSE) {
			$filename .= File::$ext;
		}

		return $filename;
	}
}