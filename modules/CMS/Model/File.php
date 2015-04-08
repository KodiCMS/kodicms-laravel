<?php namespace KodiCMS\CMS\Model;

use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\CMS\Traits\Accessor;
use SplFileInfo;
use KodiCMS\CMS\Helpers\File as FileSystem;

class File
{
	use Accessor;

	public static $ext = '.php';

	/**
	 * @var SplFileInfo
	 */
	protected $file;

	/**
	 * @var bool
	 */
	protected $isChanged = FALSE;

	/**
	 * @var bool
	 */
	protected $readOnly = FALSE;

	/**
	 * @var string
	 */
	protected $assetsPath = 'resources/layouts';

	/**
	 * @var array
	 */
	protected $attributes = [
		'roles' => ['administrator', 'developer'],
		'editor' => 'ace'
	];

	/**
	 * TODO добавить поддержку модулей
	 * @param $filename
	 * @throws Exception
	 */
	public function __construct($filename)
	{
		if($filename instanceof SplFileInfo)
		{
			$this->file = $filename;
		}
		else
		{
			if(strpos($filename, File::$ext) === FALSE)
			{
				$filename .= File::$ext;
			}

			$file = FileSystem::normalizePath(base_path($this->assetsPath . DIRECTORY_SEPARATOR . $filename));
			if(is_file($file))
			{
				$this->file = new SplFileInfo($file);
			}
			else
			{
				throw new Exception("File {$file} not found");
			}
		}

		$settingsFile = FileSystem::normalizePath(base_path($this->assetsPath . DIRECTORY_SEPARATOR . '.settings.php'));
		if(is_file($settings))
		{
			$this->attributes = array_merge($this->attributes, require $settingsFile);
		}
	}

	/**
	 * @return SplFileInfo
	 */
	public function getFile()
	{
		return $this->file;
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
	public function getPath()
	{
		return $this->file->getPath();
	}

	/**
	 * @return int
	 */
	public function getSize()
	{
		return $this->file->getSize();
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
		return (array) array_get($this->attributes, 'roles', []);
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
	 * @return bool
	 */
	public function isReadOnly()
	{
		return (bool) $this->isReadOnly || $this->file->isWritable();
	}

	public function setReadOnly()
	{
		$this->isReadOnly = TRUE;
	}

	/**
	 * @param array $roles
	 * @return array
	 */
	public function setRoles(array $roles)
	{
		return $roles;
	}

	public function setName()
	{
		
	}
}