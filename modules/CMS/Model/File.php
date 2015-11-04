<?php

namespace KodiCMS\CMS\Model;

use Date;
use View;
use Validator;
use SplFileInfo;
use SplFileObject;
use Carbon\Carbon;
use SplTempFileObject;
use KodiCMS\Support\Helpers\Text;
use Illuminate\Filesystem\Filesystem;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\CMS\Exceptions\FileModelException;
use KodiCMS\CMS\Exceptions\FileValidationException;

class File
{
    /**
     * @var string
     */
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
    protected $attributes = [
        'roles' => [
            'administrator', 'developer',
        ],
        'editor' => 'ace',
    ];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var Filesystem
     */
    protected $filesSystem;

    /**
     * @param SplFileObject|string $filename
     * @param string               $basePath
     * @param bool                 $onlyExists
     *
     * @throws FileModelException
     */
    public function __construct($filename = null, $basePath = null, $onlyExists = false)
    {
        $this->filesSystem = app('files');

        if (! is_null($basePath)) {
            $this->basePath = $basePath;
        }

        if ($filename instanceof SplFileObject) {
            $this->file = $filename;
        } elseif ($filename instanceof SplFileInfo) {
            $this->file = new SplFileObject($filename->getRealPath());
        } elseif (! is_null($filename)) {
            if (strpos($filename, self::$ext) === false) {
                $filename .= self::$ext;
            }

            if (is_file($filename)) {
                $this->file = new SplFileObject($filename);
            } elseif (is_file($this->basePath.DIRECTORY_SEPARATOR.$filename)) {
                $this->file = new SplFileObject(
                    $this->basePath.DIRECTORY_SEPARATOR.$filename
                );
            } elseif ($onlyExists) {
                throw new FileModelException("File [{$filename}] not found");
            } else {
                $this->file = new SplTempFileObject();
                $this->setName($filename);
            }
        } elseif ($onlyExists) {
            throw new FileModelException;
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
        if ($this->isNew()) {
            return;
        }

        return $filename = str_replace(self::$ext, '', $this->file->getFilename());
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
        if ($this->isNew()) {
            return;
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
        return str_replace(
            base_path(), '', $this->isNew() ? $this->getPath() : $this->getRealPath()
        );
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->isNew()
            ? $this->basePath
            : $this->file->getPath();
    }

    /**
     * @return int
     */
    public function getSize()
    {
        if ($this->isNew()) {
            $size = 0;
        } else {
            $size = $this->file->getSize();
        }

        return Text::bytes($size);
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        if ($this->isNew()) {
            return;
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
        return (array) array_get($this->attributes, 'roles', []);
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
        return Date::format(
            Carbon::createFromTimestamp($this->file->getMTime())
        );
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
        return $this->isNew() and ! $this->isDirReadOnly();
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        return ! $this->isNew() and ! $this->isReadOnly();
    }

    /**
     * @return bool
     */
    public function isExists()
    {
        return ! ($this->file instanceof SplTempFileObject);
    }

    /**
     * @return bool
     */
    public function isDirReadOnly()
    {
        return ! is_writable($this->getPath());
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return (bool) $this->readOnly || ! $this->file->isWritable();
    }

    /**
     * @param null|string $key
     *
     * @return bool
     */
    public function isChanged($key = null)
    {
        return is_null($key)
            ? ! empty($this->changed)
            : array_key_exists($key, $this->changed);
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
     *
     * @return string
     */
    public function setEditor($name)
    {
        $this->attributes['editor'] = $name;
    }

    /**
     * @param array $roles
     *
     * @return array
     */
    public function setRoles(array $roles)
    {
        $this->attributes['roles'] = $roles;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        if ($this->getName() != $name) {
            $this->changed['name'] = $this->filterName($name);
        }

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        if ($this->getContent() != $content) {
            $this->changed['content'] = $content;
        }

        return $this;
    }

    /**
     * @param array|null $settings
     *
     * @return void
     */
    public function setSettings(array $settings = null)
    {
        if (! is_null($settings)) {
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
        if (isset($this->changed['name'])) {
            $filename = $this->changed['name'];
        } else {
            $filename = $this->getFilename();
        }

        $validator = Validator::make(['name' => str_replace(self::$ext, '', $filename)], ['name' => 'required']);

        return $validator;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data = [])
    {
        foreach ($data as $key => $value) {
            $method = 'set'.camel_case($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } else {
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
        if ($this->isReadOnly() and ! $this->isNew()) {
            return false;
        }

        $status = true;

        if ($this->isNew()) {
            $newFilename = normalize_path($this->basePath.DIRECTORY_SEPARATOR.$this->changed['name']);
            $status = touch($newFilename) !== false;

            if ($status) {
                chmod($newFilename, 0777);
                $this->file = new SplFileObject($newFilename);
            }
        } elseif ($this->isChanged('name')) {
            $newFilename = normalize_path($this->getPath().'/'.$this->changed['name']);

            if ($newFilename != $this->getRealPath()) {
                $status = @$this->filesSystem->move($this->getRealPath(), $newFilename);
            }

            if ($status) {
                $this->file = new SplFileObject($newFilename);
            }
        }

        if ($status and $this->isChanged('content')) {
            $status = $this->filesSystem->put($this->getRealPath(), $this->changed['content']) !== false;
        }

        $this->changed = [];

        return $status;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $method = 'get'.ucfirst($key);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        $method = 'get'.ucfirst($key);

        return method_exists($this, $method);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'name'     => $this->getName(),
            'filename' => $this->getFilename(),
            'size'     => $this->getSize(),
            'path'     => $this->getRealPath(),
            'ext'      => $this->getExt(),
            'settings' => $this->attributes,
        ];
    }

    /**
     * @param array $parameters
     *
     * @return View
     */
    public function toView(array $parameters = [])
    {
        return view()->file($this->getRealPath())->with($parameters);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function filterName($filename)
    {
        $filename = str_replace(self::$ext, '', $filename);
        $filename = preg_replace('/[^a-zA-Z0-9\-\_\.]/', '-', strtolower($filename));
        foreach (['-', '_', '\.'] as $separator) {
            $filename = preg_replace('/'.$separator.'+/', trim($separator, '\\'), $filename);
        }

        if (strpos($filename, self::$ext) === false) {
            $filename .= self::$ext;
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
