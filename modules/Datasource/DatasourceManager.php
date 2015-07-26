<?php namespace KodiCMS\Datasource;

use Schema;
use FieldManager;
use KodiCMS\Datasource\Fields\Field;
use KodiCMS\Datasource\Model\Section;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;

class DatasourceManager extends AbstractManager
{
	/**
	 * @param array $config
	 */
	public function __construct(array $config)
	{
		$this->config = $config;

		foreach ($this->config as $type => $data)
		{
			if (!SectionType::isValid($data)) continue;

			$this->types[$type] = new SectionType($type, $data);
		}
	}

	/**
	 * @param array|null $types
	 * @return array
	 */
	public function getSections(array $types = null)
	{
		$query = Section::query();

		if(!empty($types))
		{
			$query->whereIn('type', $types);
		}

		$sections = [];

		foreach($query->get() as $section)
		{
			if (!$this->typeExists($section->type))
			{
				continue;
			}

			$sections[$section->id] = $section;
		}

		return $sections;
	}

	/**
	 * @param SectionInterface $section
	 */
	public function createTableSection(SectionInterface $section)
	{
		$this->dropSectionTable($section);

		Schema::create($section->getSectionTableName(), function ($table) use($section)
		{
			foreach ($section->getSystemFields() as $field)
			{
				$field->is_system = true;
				if($field = $section->fields()->save($field))
				{
					$field->setDatabaseFieldType($table);
				}
			}
		});
	}

	/**
	 * @param SectionInterface $section
	 */
	public function dropSectionTable(SectionInterface $section)
	{
		Schema::dropIfExists($section->getSectionTableName());
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function addNewField(SectionInterface $section, FieldInterface $field)
	{
		if($field = $section->fields()->save($field))
		{
			FieldManager::addFieldToSectionTable($section, $field);
		}
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface|Field|integer $fieldId
	 */
	public function attachField(SectionInterface $section, $fieldId)
	{
		if ($fieldId instanceof FieldInterface)
		{
			$field = $fieldId;
		}
		else if (is_integer($fieldId))
		{
			$field = Field::find($fieldId);
		}

		FieldManager::attachFieldToSection($section, $field);
	}
}