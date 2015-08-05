<?php namespace KodiCMS\Datasource\Fields\File;

use KodiCMS\Datasource\Fields\File;
use Intervention\Image\ImageManager;
use KodiCMS\Datasource\Contracts\DocumentInterface;

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
		$settings = parent::defaultSettings();
		return array_merge($settings, [
			'allowed_types' => ['jpeg', 'png', 'gif', 'bmp', 'svg'],
			'width' => 800,
			'height' => 600,
			'aspect_ratio' => true,
			'quality' => 100,
			'is_cropable' => false,

			// TODO: релазиовать добавления водяных знаков
//			'watermark' => false,
//			'watermark_file_path' => null,
//			'watermark_offset_x' => 0,
//			'watermark_offset_y' => 0,
//			'watermark_opacity' => 100
		]);
	}

	/**
	 * @param integer $width
	 */
	public function seSettingWidth($width)
	{
		intval($width);

		if ($width < 1)
		{
			$width = 0;
		}

		$this->fieldSettings['width'] = $width;
	}

	/**
	 * @param integer $height
	 */
	public function setSettingHeight($height)
	{
		intval($height);

		if ($height < 1)
		{
			$height = 0;
		}

		$this->fieldSettings['height'] = $height;
	}

	/**
	 * @param integer $quality
	 */
	public function setSettingQuality($quality)
	{
		intval($quality);

		if ($quality < 1)
		{
			$quality = 1;
		}
		elseif ($quality > 100)
		{
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

		if ($this->isImage($path))
		{
			$imagePath = $this->folderPath($path);
			$this->fieldSettings['watermark_file_path'] = $imagePath;
			$this->fieldSettings['watermark'] = true;
		}
		else
		{
			$this->fieldSettings['watermark'] = false;
			$this->fieldSettings['watermark_file_path'] = null;
		}
	}

	/**
	 * @param integer $x
	 */
	public function setSettingWatermarkOffsetX($x)
	{
		$this->fieldSettings['watermark_offset_x'] = (int) $x;
	}

	/**
	 * @param integer $y
	 */
	public function setSettingWatermarkOffsetY($y)
	{
		$this->fieldSettings['watermark_offset_y'] = (int) $y;
	}

	/**
	 * @param integer $opacity
	 */
	public function setSettingWatermarkOpacity($opacity)
	{
		intval($opacity);

		if ($opacity < 1)
		{
			$opacity = 1;
		}
		elseif ($opacity > 100)
		{
			$opacity = 100;
		}

		$this->fieldSettings['watermark_opacity'] = $opacity;
	}

	/**
	 * @return integer
	 */
	public function getWidth()
	{
		return (int) $this->getSetting('width');
	}

	/**
	 * @return integer
	 */
	public function getHeight()
	{
		return (int) $this->getSetting('height');
	}

	/**
	 * @return integer
	 */
	public function getQuality()
	{
		return (int) $this->getSetting('quality');
	}

	/**
	 * @return boolean
	 */
	public function isCropable()
	{
		return $this->getSetting('is_cropable');
	}

	/**
	 * @return boolean
	 */
	public function aspectRatio()
	{
		return $this->getSetting('aspect_ratio');
	}

	/**
	 * @param DocumentInterface $document
	 * @param value
	 *
	 * @return array|null|UploadedFile
	 */
	public function onDocumentUpdating(DocumentInterface $document, $value)
	{
		$file = parent::onDocumentUpdating($document, $value);

		if (is_null($file))
		{
			return null;
		}

		$image = (new ImageManager)->make($this->getFilePath($file));

		$width = $this->getWidth();
		$height = $this->getHeight();
		$crop = $this->isCropable();
		$aspectRatio = $this->aspectRatio();

		if ($width > 0 and empty($height))
		{
			$image->widen($width);
		}
		elseif (empty($width) and $height > 0)
		{
			$image->heighten($height);
		}
		elseif ($width > 0 and $height > 0)
		{
			$image->resize($width, $height, function ($constraint) use ($aspectRatio)
			{
				if ($aspectRatio)
				{
					$constraint->aspectRatio();
				}
			});

			if ($crop)
			{
				$image->crop($width, $height);
			}
		}

		$image->save($file, $this->getQuality());
	}
}