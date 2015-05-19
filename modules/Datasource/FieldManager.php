<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Exceptions\FieldException;

class FieldManager
{
	/**
	 * @var array
	 */
	protected $config = [];

	/**
	 * @var array
	 */
	protected $types = [];

	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;

		foreach ($this->config as $type => $data)
		{
			if (!FieldType::isValid($data)) continue;
			$this->types[$type] = new FieldType($type, $data);
		}
	}

	/**
	 * @return array
	 */
	public function getAvailableTypes()
	{
		return $this->types;
	}

	/**
	 * @param string $class
	 * @return string|null
	 */
	public function getTypeByClassName($class)
	{
		foreach ($this->getAvailableTypes() as $object)
		{
			if ($class == $object->getClass())
			{
				return $object->getType();
			}
		}

		return null;
	}

	/**
	 * @param string $type
	 * @return string|null
	 */
	public function getClassNameByType($type)
	{
		foreach ($this->getAvailableTypes() as $object)
		{
			if ($type == $object->getType())
			{
				return $object->getClass();
			}
		}

		return null;
	}

	/**
	 * @param $type
	 * @param array $settings
	 * @throws FieldException
	 */
	public function make($type, array $settings = [])
	{
		$class = $this->getClassNameByType($type);

		if (is_null($class))
		{
			throw new FieldException("Field [{$type}] not found");
		}

		return new $class($settings);
	}
}