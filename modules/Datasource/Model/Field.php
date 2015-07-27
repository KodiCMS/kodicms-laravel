<?php namespace KodiCMS\Datasource\Model;

use DB;
use FieldManager;
use KodiCMS\Datasource\FieldType;
use Illuminate\Validation\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Exceptions\FieldException;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class Field extends DatasourceModel implements FieldInterface
{
	// TODO: вынести в отдельный Observer
	protected static function boot()
	{
		parent::boot();

		static::creating(function(Field $field)
		{
			$field->position = $field->getLastPosition() + 1;
		});

		static::deleted(function(Field $field)
		{
			if ($field->isAttachedToSection())
			{
				FieldManager::dropSectionTableField($field);
			}
		});

		static::updated(function(Field $field)
		{
			if ($field->isAttachedToSection())
			{
				FieldManager::updateSectionTableField($field);
			}
		});
	}

	/**
	 * @var string
	 */
	protected $tablePrefix = '';

	/**
	 * @var array
	 */
	protected $fieldSettings = [];

	/**
	 * @var string
	 */
	protected $table = 'datasource_fields';

	/**
	 * @var FieldType
	 */
	protected $fieldType;

	/**
	 * @var null|SectionInterface
	 */
	protected $relatedSection = null;

	/**
	 * @var bool
	 */
	protected $initialized = false;

	/**
	 * @var bool
	 */
	protected $isEditable = true;

	/**
	 * @var bool
	 */
	protected $isOrderable = true;

	/**
	 * @var bool
	 */
	protected $changeableDatabaseField = true;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'ds_id', 'key', 'type', 'name', 'related_ds', 'position', 'settings'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'ds_id' => 'integer',
		'key' => 'string',
		'type' => 'string',
		'name' => 'string',
		'related_ds' => 'integer',
		'is_system' => 'boolean',
		'is_editable' => 'boolean',
		'position' => 'integer',
		'settings' => 'array'
	];

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getPosition()
	{
		return $this->position;
	}

	/**
	 * @return string
	 */
	public function getTablePrefix()
	{
		return $this->tablePrefix;
	}

	/**
	 * @param string $tablePrefix
	 */
	public function setTablePrefix($tablePrefix)
	{
		$this->tablePrefix = $tablePrefix;
	}

	/**
	 * @return string
	 */
	public function getDBKey()
	{
		return $this->getTablePrefix() . $this->getKey();
	}

	/**
	 * @return SectionInterface
	 */
	public function getSection()
	{
		return $this->section;
	}

	/**
	 * @return bool
	 */
	public function isAttachedToSection()
	{
		return !is_null($this->ds_id);
	}

	/**
	 * @return bool
	 */
	public function isSystem()
	{
		return (bool) $this->is_system;
	}

	/**
	 * @return bool
	 */
	public function isEditable()
	{
		return (bool) $this->isEditable;
	}

	/**
	 * @return bool
	 */
	public function isOrderable()
	{
		return $this->isOrderable;
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
	public function isVisible()
	{
		return $this->getSetting('headline_parameters.visible',  true);
	}

	/**
	 * @param bool $status
	 */
	public function setVisibleStatus($status)
	{
		$this->setSetting(['headline_parameters' => ['visible' => (bool) $status]]);
	}

	/**
	 * @param array $params
	 */
	public function setSettingHeadlineParameters($params)
	{
		$headlineParams = array_get($this->{$this->getSettingsProperty()}, 'headline_parameters', []);
		$this->{$this->getSettingsProperty()}['headline_parameters'] = array_merge($headlineParams, $params);
	}

	/**
	 * @return bool
	 */
	public function isSearchable()
	{
		return $this->getSetting('searchable', false);
	}

	/**
	 * @return string
	 */
	public function getDatabaseDefaultValue()
	{
		return $this->getDefaultValue();
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->getSetting('default_value', false);
	}

	/**
	 * @return mixed
	 */
	public function getHint()
	{
		return $this->getSetting('hint');
	}

	/**
	 * @return integer
	 */
	public function getRalatedSection()
	{
		return (int) $this->related_ds;
	}

	/**
	 * @param SectionHeadlineInterface $headline
	 *
	 * @return array
	 */
	public function getHeadlineParameters(SectionHeadlineInterface $headline)
	{
		return array_merge($this->getSetting('headline_parameters', []), [
			'id' => $this->getId(),
			'name' => $this->getName()
		]);
	}

	/**************************************************************************
	 * Type
	 **************************************************************************/
	/**
	 * @return \KodiCMS\Datasource\FieldType|null
	 * @throws FieldException
	 */
	public function getType()
	{
		if ($this->fieldType)
		{
			return $this->fieldType;
		}

		if (is_null($typeObject = FieldManager::getFieldTypeBy('type', $this->type)))
		{
			throw new FieldException("Datasource field type {$this->type} not found");
		}

		return $this->fieldType = $typeObject;
	}

	/**
	 * @return string
	 */
	public function getTypeTitle()
	{
		return $this->getType()->getTitle();
	}

	/**
	 * @param DocumentInterface $document
	 * @param Validator $validator
	 *
	 * @return array
	 */
	public function getValidationRules(DocumentInterface $document, Validator $validator)
	{
		$rules = [];

		if ($this->isRequired())
		{
			$rules[] = 'required';
		}

		return $rules;
	}

	/**************************************************************************
	 * Events
	 **************************************************************************/
	/**
	 * @param Blueprint $table
	 */
	public function onDatabaseCreate(Blueprint $table)
	{

	}

	/**
	 * @param Blueprint $table
	 */
	public function onDatabaseUpdate(Blueprint $table)
	{

	}

	/**
	 * @param Blueprint $table
	 */
	public function onDatabaseDrop(Blueprint $table)
	{
		$table->dropColumn($this->getDBKey());
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onSetDocumentAttribute(DocumentInterface $document, $value)
	{
		return $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetDocumentValue(DocumentInterface $document, $value)
	{
		return $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetWidgetValue(DocumentInterface $document, $value)
	{
		return $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetFormValue(DocumentInterface $document, $value)
	{
		return $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetHeadlineValue(DocumentInterface $document, $value)
	{
		return $value;
	}

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentCreating(DocumentInterface $document, $value)
	{

	}

	/**
	 * @param DocumentInterface $document
	 * @param $value
	 */
	public function onDocumentUpdating(DocumentInterface $document, $value)
	{

	}

	/**
	 * @param DocumentInterface $document
	 */
	public function onDocumentDeleting(DocumentInterface $document)
	{

	}

	/**************************************************************************
	 * Database
	 **************************************************************************/
	/**
	 * @param Builder $query
	 * @param DocumentInterface $document
	 */
	public function querySelectColumn(Builder $query, DocumentInterface $document)
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
	 * @param string $condition
	 * @param string $value
	 * @param array $params
	 */
	public function queryWhereCondition(Builder $query, $condition, $value, array $params)
	{
		switch ($condition)
		{
			case 'IN':
				$query->whereIn($this->getKey(), $value);
				break;
			case 'NOT IN':
				$query->whereNotIn($this->getKey(), $value);
				break;
			case 'NULL':
				$query->whereNull($this->getKey(), $value);
				break;
			case 'NOT NULL':
				$query->whereNotNull($this->getKey());
				break;
			case 'BETWEEN':
				$query->whereBetween($this->getKey());
				break;
			case 'NOT BETWEEN':
				$query->whereNotBetween($this->getKey(), $value);
				break;
			default:
				$query->where($this->getKey(), $condition, $value);
		}
	}

	/**
	 * @return bool
	 */
	public function isChangeableDatabaseField()
	{
		return $this->changeableDatabaseField;
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->string($this->getDBKey(), $this->getSetting('length'));
	}

	/**************************************************************************
	 * Relations
	 **************************************************************************/
	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function section()
	{
		return $this->belongsTo('KodiCMS\Datasource\Model\Section', 'ds_id');
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function relatedSection()
	{
		return $this->belongsTo('KodiCMS\Datasource\Model\Section', 'related_ds');
	}

	/**************************************************************************
	 * Other
	 **************************************************************************/
	/**
	 * @return string
	 */
	protected function getSettingsProperty()
	{
		return 'fieldSettings';
	}

	/**
	 * @return float|int
	 */
	public function getLastPosition()
	{
		return DB::table($this->getTable())->where('ds_id', $this->ds_id)->max('position');
	}

	/**
	 * @return DatasourceManagerInterface
	 */
	public static function getManagerClass()
	{
		return FieldManager::getFacadeRoot();
	}

	/**************************************************************************
	 * Render
	 **************************************************************************/
	/**
	 * @param DocumentInterface $document
	 *
	 * @return string
	 */
	public function renderBackendTemplate(DocumentInterface $document, $template = null)
	{
		if (is_null($template))
		{
			$template = 'datasource::document.field.' . $this->getType()->getType();
		}

		return view($template, [
			'value' => $document->getFormValue($this->getDBKey()),
			'field' => $this,
			'document' => $document,
			'section' => $document->getSection()
		]);
	}

	/**************************************************************************
	 * Widgets
	 **************************************************************************/

	public function getWidgetTypes()
	{
		return [];
	}
}