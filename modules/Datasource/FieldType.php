<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\FieldTypeInterface;

class FieldType implements FieldTypeInterface
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
	 * @param string $type
	 * @param array $settings
	 */
	public function __construct($type, array $settings)
	{
		foreach(array_only($settings, ['class', 'type', 'title']) as $key => $value)
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
	 * @return string
	 */
	public function getClassName()
	{
		return $this->class;
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