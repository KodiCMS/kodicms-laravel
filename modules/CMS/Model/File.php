<?php namespace KodiCMS\CMS\Model;

use Carbon\Carbon;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\CMS\Exceptions\FileModelException;
use KodiCMS\CMS\Exceptions\FileValidationException;
use KodiCMS\CMS\Helpers\File as FileSystem;
use KodiCMS\CMS\Helpers\Text;
use SplFileInfo;
use SplFileObject;
use SplTempFileObject;
use Validator;
use Date;
use View;

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
	protected $isChanged = false;

	/**
	 * @var array
	 */
	protected $changed = [];

	/**
	 * @var bool
	 */
	protected $readOnly = false;

	/**
	 * @var
	 */
	protected $basePath;

	/**
	 * @var array
	 */
	protected $attributes = ['roles' => ['administrator', 'developer'], 'editor' => 'ace'];

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * TODO добавить поддержку модулей
	 *
	 * @param SplFileObject|string $filename
	 * @param string $basePath
	 * @param bool $onlyExists
	 * @throws FileModelException
	 */
	public function __construct($filename = null, $basePath = null, $onlyExists = false)
	{
		if (!is_null($basePath))
		{
			$this->basePath = $basePath;
		}

		if ($filename instanceof SplFileObject)
		{
			$this->file = $filename;
		}
		else if ($filename instanceof SplFileInfo)
		{
			$this->file = new SplFileObject($file->getRealPath());
		}
		else if (!is_null($filename))
		{
			if (strpos($filename, File::$ext) === false)
			{
				$filename .= File::$ext;
			}

			if (is_file($filename))
			{
				$this->file = new SplFileObject($filename);
			}
			else if (is_file($this->basePath . DIRECTORY_SEPARATOR . $filename))
			{
				$this->file = new SplFileObject($this->basePath . DIRECTORY_SEPARATOR . $filename);
			}
			else if ($onlyExists)
			{
				throw new FileModelException("File [{$filename}] not found");
			}
			else
			{
				$this->file = new SplTempFileObject();
				$this->setName($filename);
			}
		}
		else if($onlyExists)
		{
			throw new FileModelException;
		}
		else
		{
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
		if ($this->isNew())
		{
			return null;
		}

		return $filename = str_replace(File::$ext, '', $this->file->getFilename());;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return str_slug($this->getName());
	}

	/**
	 * @return string
	 */
	public function getFilename()
	{
		if ($this->isNew())
		{
			return null;
		}

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
		if($this->isNew())
		{
			$size = 0;
		}
		else
		{
			$size = $this->file->getSize();
		}

		return Text::bytes($size);
	}

	/**
	 * @return null|string
	 */
	public function getContent()
	{
		if ($this->isNew())
		{
			return null;
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
		return Date::format(Carbon::createFromTimestamp($this->file->getMTime()));
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
	public function isCreatable()
	{
		return $this->isNew() and !$this->isDirReadOnly();
	}

	/**
	 * @return bool
	 */
	public function isEditable()
	{
		return !$this->isNew() and !$this->isReadOnly();
	}

	/**
	 * @return bool
	 */
	public function isExists()
	{
		return !($this->file instanceof SplTempFileObject);
	}

	/**
	 * @return bool
	 */
	public function isDirReadOnly()
	{
		return !is_writable($this->getPath());
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
	public function isChanged($key = null)
	{
		return is_null($key) ? !empty($this->changed) : array_key_exists($key, $this->changed);
	}

	/**
	 * @return void
	 */
	public function setReadOnly()
	{
		$this->isReadOnly = true;
	}

	/**
	 * @param string $name
	 * @return string
	 */
	public function setEditor($name)
	{
		$this->attributes['editor'] = $name;
	}

	/**
	 * @param array $roles
	 * @return array
	 */
	public function setRoles(array $roles)
	{
		$this->attributes['roles'] = $roles;
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
	public function setSettings(array $settings = null)
	{
		if (!is_null($settings))
		{
			$this->attributes = $settings;
		}
	}

	/**
	 * @return bool
	 */
	public function delete()
	{
		return @unlink($this->getRealPath());
	}

	/**
	 * @return bool
	 * @throws FileValidationException
	 */
	public function validator()
	{
		if (isset($this->changed['name']))
		{
			$filename = $this->changed['name'];
		} else
		{
			$filename = $this->getFilename();
		}

		$validator = Validator::make(['name' => str_replace(File::$ext, '', $filename)], ['name' => 'required']);

		return $validator;
	}

	/**
	 * @param array $data
	 * @return $this
	 */
	public function fill(array $data = [])
	{
		foreach ($data as $key => $value)
		{
			$method = 'set' . ucfirst($key);
			if (method_exists($this, $method))
			{
				$this->{$method}($value);
			} else
			{
				$this->changed[$key] = $value;
			}
		}

		return $this;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	public function save()
	{
		if ($this->isReadOnly() AND !$this->isNew())
		{
			return false;
		}

		$status = true;

		if ($this->isNew())
		{
			$newFilename = FileSystem::normalizePath($this->basePath . DIRECTORY_SEPARATOR . $this->changed['name']);
			$status = touch($newFilename) !== false;

			if ($status)
			{
				chmod($newFilename, 0777);
				$this->file = new SplFileObject($newFilename);
			}
		}
		else if ($this->isChanged('name'))
		{
			$newFilename = FileSystem::normalizePath($this->getPath() . '/' . $this->changed['name']);
			$status = @app('files')->move($this->getRealPath(), $newFilename);

			if ($status)
			{
				$this->file = new SplFileObject($newFilename);
			}
		}

		if ($status AND $this->isChanged('content'))
		{
			$status = app('files')->put($this->getRealPath(), $this->changed['content']) !== false;
		}

		$this->changed = [];

		return $status;
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function __get($key)
	{
		$method = 'get' . ucfirst($key);
		if (method_exists($this, $method))
		{
			return $this->{$method}();
		}
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function __isset($key)
	{
		$method = 'get' . ucfirst($key);

		return method_exists($this, $method);
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'name' => $this->getName(),
			'filename' => $this->getFilename(),
			'size' => $this->getSize(),
			'path' => $this->getRealPath(),
			'ext' => $this->getExt(),
			'settings' => $this->attributes
		];
	}

	/**
	 * @param array $paramters
	 * @return View
	 */
	public function toView(array $paramters = [])
	{
		return view()->file($this->getRealPath())->with($paramters);
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
		foreach (['-', '_', '\.'] as $separator)
		{
			$filename = preg_replace('/' . $separator . '+/', trim($separator, '\\'), $filename);
		}

		if (strpos($filename, File::$ext) === false)
		{
			$filename .= File::$ext;
		}

		return $filename;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getContent();
	}
}