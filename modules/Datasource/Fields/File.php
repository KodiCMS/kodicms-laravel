<?php

namespace KodiCMS\Datasource\Fields;

use Request;
use Illuminate\Validation\Validator;
use Illuminate\Filesystem\Filesystem;
use KodiCMS\Datasource\Exceptions\FieldException;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File extends Primitive
{
    /**
     * @var bool
     */
    protected $changeableDatabaseField = false;

    /**
     * @var string
     */
    protected $folderPath;

    /**
     * @var string
     */
    protected $folderRelativePath;

    /**
     * @var bool
     */
    protected $isRemoveFile = false;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return ['allowed_types' => [], 'max_file_size' => 1048576];
    }

    /**
     * @param Filesystem $files
     */
    public function onInit(Filesystem $files)
    {
        $this->files = $files;

        $this->folderRelativePath = "datasource/{$this->section_id}/{$this->getDBKey()}/";
        $this->folderPath = normalize_path(public_path($this->folderRelativePath));
    }

    /**
     * @return array
     */
    public function getMimeTypes()
    {
        $mimes = array_keys(config('mimes', []));

        return array_combine($mimes, $mimes);
    }

    /**
     * @param string $filePath
     *
     * @return bool
     */
    public function isImage($filePath)
    {
        $filePath = $this->getFilePath($filePath);

        if (! file_exists($filePath) or is_dir($filePath)) {
            return false;
        }

        $size = getimagesize($filePath);

        if (! $size) {
            return false;
        }

        $imageType = $size[2];

        if (in_array($imageType, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP])) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFolder()
    {
        return $this->folderPath;
    }

    /**
     * @return int
     */
    public function getMaxFileSize()
    {
        return $this->getSetting('max_file_size');
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return (array) $this->getSetting('allowed_types');
    }

    /**
     * @param $filePath
     *
     * @return string
     */
    public function getFilePath($filePath)
    {
        return normalize_path(public_path($filePath));
    }

    /**
     * @param $size
     */
    public function setSettingMaxFileSize($size)
    {
        $this->fieldSettings['max_file_size'] = (int) $size;
    }

    /**
     * @param array $types
     */
    public function setSettingAllowedTypes($types)
    {
        if (! is_array($types)) {
            $types = explode(',', $types);
        }

        foreach ($types as $i => $type) {
            $type = trim($type);
            if (empty($type) or ! preg_match('~^[A-Za-z0-9_\\-]+$~', $type) or ! $this->checkDisallowedTypes($type)) {
                unset($types[$i]);
            }
        }

        $this->fieldSettings['allowed_types'] = $types;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function checkDisallowedTypes($type)
    {
        $disallowedTypes = explode(',', '/^php/,/^phtm/,py,pl,/^asp/,htaccess,cgi,_wc,/^shtm/,/^jsp/');
        foreach ($disallowedTypes as $disallowed) {
            if (((strpos($disallowed, '/') !== false) and preg_match($disallowed, $type)) or $disallowed == $type) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param DocumentInterface $document
     * @param Validator         $validator
     *
     * @return array
     */
    public function getValidationRules(DocumentInterface $document, Validator $validator)
    {
        $rules = parent::getValidationRules($document, $validator);

        if (! empty($allowedTypes = $this->getAllowedTypes())) {
            $rules[] = 'mimes:'.implode(',', $allowedTypes);
        }

        $rules[] = 'max:'.$this->getMaxFileSize();

        return $rules;
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return null|string
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
        parent::onDocumentFill($document, $value);

        if (($file = $document->{$this->getDBKey()}) instanceof UploadedFile) {
            $this->onDocumentDeleting($document);
            $document->{$this->getDBKey()} = $filePath = $this->uploadFile($file);
        }
    }

    /**
     * @param DocumentInterface $document
     * @param                   value
     *
     * @return array|null|UploadedFile
     */
    public function onDocumentUpdating(DocumentInterface $document, $value)
    {
        $this->isRemoveFile = (bool) Request::get($this->getDBKey().'_remove');

        if ($this->isRemoveFile) {
            $this->onDocumentDeleting($document);
        }
    }

    /**
     * @param DocumentInterface $document
     */
    public function onDocumentDeleting(DocumentInterface $document)
    {
        parent::onDocumentDeleting($document);

        if (! empty($filePath = $document->getOriginal($this->getDBKey()))) {
            $this->files->delete($this->getFilePath($filePath));
            $document->{$this->getDBKey()} = '';
        }
    }

    public function onCreated()
    {
        $this->makeDirectory();
    }

    public function onDeleted()
    {
        $this->deleteDirectory();
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        if ($this->isImage($value)) {
            $link = link_to($value, \HTML::image($value, null, ['style' => 'max-height: 50px']), [
                'target' => '_blank',
                'class'  => 'popup img-thumbnail',
            ]);
        } else {
            $link = link_to($value, trans('datasource::fields.file.view_file'), ['target' => '_blank']);
        }

        return ! is_null($value) ? $link : null;
    }

    /**
     * @return bool
     * @throws FieldException
     */
    protected function makeDirectory()
    {
        if (is_dir($this->getFolder())) {
            return true;
        }

        if (! $this->files->makeDirectory($this->getFolder(), 0755, true)) {
            throw new FieldException("Can't create directory [{$this->getFolder()}]");
        }

        return true;
    }

    /**
     * @return bool
     * @throws FieldException
     */
    protected function deleteDirectory()
    {
        if (! $this->files->deleteDirectory($this->getFolder())) {
            throw new FieldException("Can't delete directory [{$this->getFolder()}]");
        }

        return true;
    }

    /**
     * @param UploadedFile $file
     *
     * @return string
     */
    protected function uploadFile(UploadedFile $file)
    {
        if ($file->isValid()) {
            $ext = $file->getClientOriginalExtension();
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename .= '_'.uniqid().'.'.$ext;

            $file->move($this->getFolder(), $filename);

            return $this->folderRelativePath.$filename;
        }

        return;
    }
}
