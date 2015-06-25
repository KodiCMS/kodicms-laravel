<?php namespace KodiCMS\Datasource\Fields;

use FieldManager;
use KodiCMS\Support\Traits\Settings;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Model\Field as FieldModel;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;

abstract class Field implements FieldInterface
{
	use Settings {
		setSetting as protected setSettingTrait;
	}

	/**
	 * @var string
	 */
	protected $tablePreffix = '';

	/**
	 * @var FieldModel
	 */
	protected $model;

	/**
	 * @var string
	 */
	protected $template = null;

	/**
	 * @var null|SectionInterface
	 */
	protected $relatedSection = null;

	/**
	 * @var array|mixed
	 */
	protected $settings = [];

	/**
	 * @param FieldModel $model
	 * @param array $attributes
	 */
	public function __construct(FieldModel $model = null, array $attributes = [])
	{
		if (is_null($model))
		{
			$attributes['type'] = FieldManager::getTypeByClassName(get_called_class());
			$model = new FieldModel($attributes);
		}

		$this->model = $model;
		if (!empty($model->settings))
		{
			$this->settings = $model->settings;
		}
		else
		{
			$this->setSettings($this->defaultSettings());
		}
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [];
	}

	/**
	 * @return FieldModel
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->model->id;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->model->type;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->model->key;
	}

	/**
	 * @return string
	 */
	public function getDBKey()
	{
		return $this->getTablePreffix() . $this->getKey();
	}

	/**
	 * @return bool
	 */
	public function isSystem()
	{
		return (bool) $this->model->is_system;
	}

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->getSetting('is_required', false);
	}

	/**
	 * @return bool
	 */
	public function isVisibled()
	{
		return $this->getSetting('is_visible', false);
	}

	/**
	 * @return bool
	 */
	public function isSearchable()
	{
		return $this->getSetting('is_searchable', false);
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->model->getSetting('default_value', false);
	}

	/**
	 * @return string
	 */
	public function getTablePreffix()
	{
		return $this->tablePreffix;
	}

	/**
	 * @param string $tablePreffix
	 */
	public function setTablePreffix($tablePreffix)
	{
		$this->tablePreffix = $tablePreffix;
	}

	/**
	 * @param bool $status
	 */
	public function setVisibleStatus($status)
	{
		$this->is_visible = (bool) $status;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertValueToHTML($value)
	{
		return $value;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertValueToSQL($value)
	{
		return $value;
	}

	/**
	 * @param mixed $value
	 * @return string
	 */
	public function convertToHeadline($value)
	{
		return $value;
	}

	/**************************************************************************
	 * Events
	 **************************************************************************/

	/**
	 * @param DocumentInterface $document
	 * @param array $values
	 */
	public function onSetDocmuentValue(DocumentInterface $document, array $values)
	{
		$document->setFieldValue($this->getKey(), array_get($values, $this->getKey()));
	}

	/**
	 * @param DocumentInterface $document
	 * @param Validator $validator
	 * @param $value
	 */
	public function onValidateDocument(DocumentInterface $document, Validator $validator, $value)
	{

	}

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentCreate(DocumentInterface $document, $value)
	{

	}

	/**
	 * @param DocumentInterface $oldDocument
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentUpdate(DocumentInterface $oldDocument, DocumentInterface $document, $value)
	{

	}

	/**
	 * @param DocumentInterface $document
	 */
	public function onDocumentRemove(DocumentInterface $document)
	{

	}

	/**************************************************************************
	 * System
	 **************************************************************************/
	/**
	 * @return FieldInterface
	 * @throws \KodiCMS\Datasource\Exceptions\FieldException
	 */
	public function create()
	{
		$field = $this->getModel()->create();

		return $field->toField();
	}

	/**************************************************************************
	 * Database
	 **************************************************************************/
	/**
	 * @param Builder $query
	 */
	public function querySelectColumn(Builder $query)
	{
		$query->selectRaw("{$this->getDBKey()} as {$this->getKey()}");
	}

	/**
	 * @param Builder $query
	 * @param string $dir
	 */
	public function queryOrderBy(Builder $query, $dir = 'asc')
	{
		$query->orderBy($this->getKey(), $dir);
	}

	/**
	 * @param Builder $query
	 * @param $condition
	 * @param $value
	 */
	public function queryWhereCondition(Builder $query, $condition, $value)
	{
		$query->where($this->getKey(), $condition, $value);
	}

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		$table->string($this->getDBKey());
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
}