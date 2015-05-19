<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\SectionTypeInterface;

class SectionType implements SectionTypeInterface
{
	/**
	 * @param array $settings
	 * @return bool
	 */
	public static function isValid(array $settings)
	{
		if(!isset($settings['class'])) return false;
		return true;
	}

	/**
	 * @var string
	 */
	protected $class;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string
	 */
	protected $document = null;

	/**
	 * @param string $type
	 * @param array $settings
	 */
	public function __construct($type, array $settings)
	{
		foreach(array_only($settings, ['class', 'type', 'title', 'document']) as $key => $value)
		{
			$this->{$key} = $value;
		}
	}

	/**
	 * @return bool
	 */
	public function isExists()
	{
		return class_exists($this->class);
	}

	/**
	 * @return bool
	 */
	public function isDocumentClassExists()
	{
		return class_exists($this->document);
	}

	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class;
	}

	/**
	 * @return string
	 */
	public function getDocumentClassName()
	{
		if($this->isDocumentClassExists())
		{
			return $this->document;
		}

		return 'KodiCMS\Datasource\Document';
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}
}