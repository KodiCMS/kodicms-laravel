<?php namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Exceptions\FieldException;

class FieldManager {

	/**
	 * @return array
	 */
	public function getAvailableTypes()
	{
		$types = [];
		foreach (config('fields', []) as $type => $data)
		{
			if (!FieldType::isValid($data)) continue;

			$types[$type] = new FieldType($type, $data);
		}

		return $types;
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

	public function make($type, array $parameters = [])
	{
		$class = $this->getClassNameByType($type);

		if (is_null($class))
		{
			throw new FieldException("Field [{$type}] not found");
		}

		new $class($parameters);
	}
}