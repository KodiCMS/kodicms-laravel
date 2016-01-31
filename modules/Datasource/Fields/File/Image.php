<?php

namespace KodiCMS\Datasource\Fields\File;

use Intervention\Image\ImageManager;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Fields\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Image extends File
{
    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['crop', 'watermark', 'aspect_ratio'];
    }

    /**
     * @return array
     */
    public function defaultSettings()
    {
        return array_merge(parent::defaultSettings(), [
            'allowed_types'     => ['jpeg', 'png', 'gif', 'bmp', 'svg'],
            'width'             => 800,
            'height'            => 600,
            'aspect_ratio'      => true,
            'quality'           => 100,
            'is_cropable'       => false,
            'same_image_fields' => [],

            // TODO: релазиовать добавления водяных знаков
//			'watermark' => false,
//			'watermark_file_path' => null,
//			'watermark_offset_x' => 0,
//			'watermark_offset_y' => 0,
//			'watermark_opacity' => 100
        ]);
    }

    /**
     * @param int $width
     */
    public function setSettingWidth($width)
    {
        intval($width);

        if ($width < 1) {
            $width = 0;
        }

        $this->fieldSettings['width'] = $width;
    }

    /**
     * @param int $height
     */
    public function setSettingHeight($height)
    {
        intval($height);

        if ($height < 1) {
            $height = 0;
        }

        $this->fieldSettings['height'] = $height;
    }

    /**
     * @param int $quality
     */
    public function setSettingQuality($quality)
    {
        intval($quality);

        if ($quality < 1) {
            $quality = 1;
        } elseif ($quality > 100) {
            $quality = 100;
        }

        $this->fieldSettings['quality'] = $quality;
    }

    /**
     * @param string $path
     */
    public function setSettingWatermarkFilePath($path)
    {
        $path = normalize_path($path);

        if ($this->isImage($path)) {
            $imagePath = $this->folderPath($path);
            $this->fieldSettings['watermark_file_path'] = $imagePath;
            $this->fieldSettings['watermark'] = true;
        } else {
            $this->fieldSettings['watermark'] = false;
            $this->fieldSettings['watermark_file_path'] = null;
        }
    }

    /**
     * @param int $x
     */
    public function setSettingWatermarkOffsetX($x)
    {
        $this->fieldSettings['watermark_offset_x'] = (int) $x;
    }

    /**
     * @param int $y
     */
    public function setSettingWatermarkOffsetY($y)
    {
        $this->fieldSettings['watermark_offset_y'] = (int) $y;
    }

    /**
     * @param int $opacity
     */
    public function setSettingWatermarkOpacity($opacity)
    {
        intval($opacity);

        if ($opacity < 1) {
            $opacity = 1;
        } elseif ($opacity > 100) {
            $opacity = 100;
        }

        $this->fieldSettings['watermark_opacity'] = $opacity;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return (int) $this->getSetting('width');
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return (int) $this->getSetting('height');
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return (int) $this->getSetting('quality');
    }

    /**
     * @return bool
     */
    public function isCropable()
    {
        return $this->getSetting('is_cropable');
    }

    /**
     * @return bool
     */
    public function aspectRatio()
    {
        return $this->getSetting('aspect_ratio');
    }

    /**
     * @return array
     */
    public function getSectionImageFields()
    {
        return array_map(function ($field) {
            return $field->getName();
        }, array_filter($this->getSection()->getFields()->getFields(), function ($field) {
            return ($field instanceof Image) and $field->getId() != $this->getId();
        }));
    }

    /**
     * @return array
     */
    public function getSelectedSameImageFields()
    {
        return (array) $this->getSetting('same_image_fields');
    }

    /**
     * @param $file
     *
     * @return bool|string
     */
    public function copyImageFile($file)
    {
        if ($this->files->exists($this->getFilePath($file))) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            $filename = uniqid().'.'.$ext;
            $this->files->copy($this->getFilePath($file), $this->getFolder().$filename);

            return $this->folderRelativePath.$filename;
        }

        return false;
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return void
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
        parent::onDocumentFill($document, $value);

        if (($value instanceof UploadedFile) and ! empty($this->getSelectedSameImageFields())) {
            $fields = $this->getSection()->getFields();
            foreach ($this->getSelectedSameImageFields() as $sameField) {
                if (! is_null($field = $fields->offsetGet($sameField)) and ! ($document->{$sameField} instanceof UploadedFile)) {
                    if ($filePath = $field->copyImageFile($document->{$this->getDBKey()})) {
                        $document->{$sameField} = $filePath;
                    }
                }
            }
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
        parent::onDocumentUpdating($document, $value);

        if (is_null($value) or $this->isRemoveFile) {
            return;
        }

        $image = (new ImageManager)->make($this->getFilePath($value));

        $width = $this->getWidth();
        $height = $this->getHeight();
        $crop = $this->isCropable();
        $aspectRatio = $this->aspectRatio();

        if ($width > 0 and empty($height)) {
            $image->widen($width);
        } elseif (empty($width) and $height > 0) {
            $image->heighten($height);
        } elseif ($width > 0 and $height > 0) {
            $image->resize($width, $height, function ($constraint) use ($aspectRatio) {
                if ($aspectRatio) {
                    $constraint->aspectRatio();
                }
            });

            if ($crop) {
                $image->crop($width, $height);
            }
        }

        $image->save($value, $this->getQuality());
    }
}
