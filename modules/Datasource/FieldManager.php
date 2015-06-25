<?php namespace KodiCMS\Datasource;

use Schema;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;

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
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function attachFieldToSection(SectionInterface $section, FieldInterface $field)
	{
		$this->addFieldToSectionTable($section, $field);
		$field->getModel()->update([
			'ds_id' => $section->getId()
		]);
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function addFieldToSectionTable(SectionInterface $section, FieldInterface $field)
	{
		if (Schema::hasColumn($section->getTableName(), $field->getDBKey()))
		{
			// TODO throw Exception
		}

		Schema::table($section->getTableName(), function($table) use($field)
		{
			$field->setDatabaseFieldType($table);
		});
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function updateSectionTableField(SectionInterface $section, FieldInterface $field)
	{
		if (!Schema::hasColumn($section->getTableName(), $field->getDBKey()))
		{
			// TODO throw Exception
		}

		Schema::table($section->getTableName(), function($table) use($field)
		{
			$field->setDatabaseFieldType($table)->change();
		});
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function dropSectionTableField(SectionInterface $section, FieldInterface $field)
	{
		if (!Schema::hasColumn($section->getTableName(), $field->getDBKey()))
		{
			// TODO throw Exception
		}

		Schema::table($section->getTableName(), function($table) use($field)
		{
			$table->dropColumn($field->getDBKey());
		});
	}
}