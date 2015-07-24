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
		return (bool) $this->is_editable;
	}

	/**
	 * @return bool
	 */
	public function isRequire()
	{
		return $this->getSetting('is_required', false);
	}

	/**
	 * @return bool
	 */
	public function isVisible()
	{
		return $this->getSetting('is_visible', false);
	}

	/**
	 * @param bool $status
	 */
	public function setVisibleStatus($status)
	{
		$this->setSetting('is_visible', (bool) $status);
	}

	/**
	 * @return bool
	 */
	public function isSearchable()
	{
		return $this->getSetting('is_searchable', false);
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

	/**************************************************************************
	 * Events
	 **************************************************************************/

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
}