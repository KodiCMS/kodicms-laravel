<?php namespace KodiCMS\Datasource;

use Schema;
use FieldManager;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Fields\Field;
use KodiCMS\Datasource\Model\Section;

class DatasourceManager {

	/**
	 * @var array
	 */
	protected $config = [];

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
	 * @param $type
	 * @return SectionType|null
	 */
	public function getTypeObject($type)
	{
		foreach ($this->getAvailableSectionTypes() as $object)
		{
			if ($type == $object->getType())
			{
				return $object;
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
		foreach ($this->getAvailableSectionTypes() as $object)
		{
			if ($type == $object->getType())
			{
				return $object->getClass();
			}
		}

		return null;
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	public function typeExists($type)
	{
		return isset($this->types[$type]);
	}

	public function getSectionsTree()
	{

	}

	public function getSections(array $types = null)
	{
		$query = DB::table('datasource');

		if(!empty($types))
		{
			$query->whereIn('type', $types);
		}

		$sections = [];

		foreach($query->get() as $row)
		{
			if(!$this->typeExists($row->type)) continue;
			$sections[$row->id] = $section = new Section((array) $row);
		}

		return $sections;
	}

	/**
	 * @param SectionInterface $section
	 */
	public function createTableSection(SectionInterface $section)
	{
		$this->dropSectionTable($section);

		Schema::create($section->getTableName(), function ($table) use($section)
		{
			foreach ($section->systemFields() as $field)
			{
				$model = $field->getModel()->fill(['is_system' => true]);
				if($field = $section->getModel()->fields()->save($model))
				{
					$field->toField()->setDatabaseFieldType($table);
				}
			}
		});
	}

	/**
	 * @param SectionInterface $section
	 */
	public function dropSectionTable(SectionInterface $section)
	{
		Schema::dropIfExists($section->getTableName());
	}

	/**
	 * @param SectionInterface $section
	 * @param FieldInterface $field
	 */
	public function addNewField(SectionInterface $section, FieldInterface $field)
	{
		$model = $field->getModel();
		if($field = $section->getModel()->fields()->save($model))
		{
			FieldManager::addFieldToSectionTable($section, $field->toField());
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
			$field = Field::find($fieldId)->toField();
		}
		elseif ($fieldId instanceof Field)
		{
			$field = $fieldId->toField();
		}

		FieldManager::attachFieldToSection($section, $field);
	}

	/**
	 * @param integer $sectionId
	 * @param integer $folderId
	 * @return bool
	 */
	public function moveSectionToFolder($sectionId, $folderId)
	{
		Section::findOrFail($sectionId)->update([
			'folder_id' => (int) $folderId
		]);

		return true;
	}

	/**
	 * @return array
	 */
	public function getAvailableSectionTypes()
	{
		return $this->types;
	}
}