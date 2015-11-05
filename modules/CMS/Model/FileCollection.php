<?php

namespace KodiCMS\CMS\Model;

use Iterator;
use SplFileInfo;
use ModulesFileSystem;

class FileCollection implements Iterator
{
    /**
     * @var SplFileInfo
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

    /**
     * @var string
     */
    protected $fileClass = File::class;

    /**
     * @var string
     */
    protected $resourceFolder;

    /**
     * @param string $directory
     * @param string $resourceFolder
     */
    public function __construct($directory, $resourceFolder)
    {
        $this->directory = new SplFileInfo($directory);
        $this->resourceFolder = 'resources'.DIRECTORY_SEPARATOR.$resourceFolder;

        $this->settings = $this->getSettingsFile();

        $this->listFiles();
    }

    protected function listFiles()
    {
        foreach (ModulesFileSystem::listFiles($this->resourceFolder) as $relativePath => $splFile) {
            $this->addFile($splFile);
        }
    }

    /**
     * @param SplFileInfo $file
     *
     * @return File
     */
    public function addFile($file)
    {
        $file = new $this->fileClass($file);
        $file->setSettings(array_get($this->settings, $file->getFilename()));

        return $this->files[$file->getRealPath()] = $file;
    }

    /**
     * @return bool
     */
    public function isReadOnly()
    {
        return ! $this->directory->isWritable();
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
        return normalize_path($this->directory.DIRECTORY_SEPARATOR.'.settings.php');
    }

    /**
     * @return array
     */
    public function getSettingsFile()
    {
        $settingsFile = $this->getSettingsFilePath();

        if (is_file($settingsFile)) {
            return (array) include $settingsFile;
        } else {
            return [];
        }
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return count($this->files);
    }

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function findFile($filename)
    {
        if (strpos($filename, File::$ext) !== false) {
            $method = 'getFilename';
        } else {
            $method = 'getName';
        }

        foreach ($this->files as $file) {
            if ($file->{$method}() == $filename) {
                return $file;
            }
        }

        return false;
    }

    /**
     * @return File
     */
    public function newFile()
    {
        return $this->newFiles[] = new $this->fileClass(null, $this->getRealPath());
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
        foreach ($this->files as $file) {
            $this->saveFile($file);
        }

        $this->saveSettings();

        return $this;
    }

    /**
     * @param File $file
     *
     * @return $this
     */
    public function saveFile(File $file)
    {
        $file->save();
        $this->files[$file->getRealPath()] = $file;

        return $this;
    }

    /**
     * @return int
     */
    public function saveSettings()
    {
        $status = is_file($this->getSettingsFilePath());

        if (! $status) {
            $status = touch($this->getSettingsFilePath()) !== false;
        }

        if ($status and is_writable($this->getSettingsFilePath())) {
            $settings = [];
            foreach ($this->files as $file) {
                $settings[$file->getFilename()] = $file->getSettings();
            }

            $data = '<?php'.PHP_EOL;
            $data .= 'return ';
            $data .= var_export($settings, true);
            $data .= ';';

            return file_put_contents($this->getSettingsFilePath(), $data);
        }
    }

    /**
     * @return array
     */
    public function getHTMLSelectChoices()
    {
        $choices = [
            trans('cms::core.label.not_set'),
        ];

        foreach ($this as $layout) {
            $choices[$layout->getName()] = $layout->getName();
        }

        return $choices;
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
