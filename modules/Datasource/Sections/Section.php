<?php namespace KodiCMS\Datasource\Sections;

use DB;
use KodiCMS\Datasource\Fields\Primary;
use KodiCMS\Datasource\Fields\String;
use KodiCMS\Datasource\Model\Field;
use Schema;
use DatasourceManager;
use KodiCMS\CMS\Traits\Settings;
use KodiCMS\Datasource\SectionType;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Model\Section as SectionModel;

class Section implements SectionInterface
{
	use Settings {
		setSetting as protected setSettingTrait;
	}

	protected $tableName = 'test';

	/**
	 * @var SectionModel
	 */
	protected $model;

	/**
	 * @var SectionType
	 */
	protected $type;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @param SectionModel $model
	 */
	public function __construct(SectionModel $model)
	{
		$this->model = $model;
		$this->type = DatasourceManager::getTypeObject($model->type);
		$this->settings = $model->settings;

		$fields = $this->model->fields();
		foreach($fields->get() as $field)
		{
			$this->fields[$field->id] = $field->toField();
		}
	}

	/**
	 * @return array
	 */
	public function systemFields()
	{
		return [
			new Primary(null, [
				'key' => 'id',
				'name' => 'ID'
			]),
			new String(null, [
				'key' => 'header',
				'name' => 'Header'
			])
		];
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->model->id;
	}

	/**
	 * @return SectionType|null
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setSetting($name, $value = null)
	{
		$return = $this->setSettingTrait($name, $value);
		$this->model->settings = $this->settings;
		return $return;
	}

	public function getTableName()
	{
		return $this->tableName . $this->getId();
	}

	public function createTable()
	{
		$fields = $this->getFields();
		Schema::dropIfExists($this->getTableName());
		Schema::create($this->getTableName(), function($table) use($fields)
		{
			foreach($fields as $field)
			{
				$field->setDatabaseFieldType($table);
			}
		});
	}

	public function createSystemFields()
	{
		foreach ($this->systemFields() as $field)
		{
			if($field = $this->model->fields()->save($field->getModel()))
			{
				$this->fields[$field->id] = $field->toField();
			}
		}

		return $this;
	}

	/**
	 * @param $fieldId
	 */
	public function attachField($fieldId)
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

		$sectionId = $this->getId();

		if (!Schema::hasColumn($field->getDBKey()))
		{
			Schema::table($this->getTableName(), function ($table) use($field, $sectionId)
			{
				$field->setDatabaseFieldType($table);
				$field->attachToSection($sectionId);
			});
		}
	}
}