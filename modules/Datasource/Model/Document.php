<?php namespace KodiCMS\Datasource\Model;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldTypeDateInterface;

class Document extends Model implements DocumentInterface
{
	/**
	 * @var SectionInterface
	 */
	protected $section;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = false;

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = null;

	/**
	 * @var array
	 */
	protected $sectionFields = [];

	/**
	 * @var array
	 */
	protected $sectionFieldsIds = [];

	/**
	 * @param array $attributes
	 * @param SectionInterface|null $section
	 */
	public function __construct($attributes = [], SectionInterface $section = null)
	{
		if (!is_null($section))
		{
			$this->section = $section;
			$this->table = $this->section->getSectionTableName();

			$this->primaryKey = $section->getDocumentPrimaryKey();
			if (!is_null($this->primaryKey))
			{
				$this->incrementing = true;
			}

			foreach ($this->section->getFields() as $field)
			{
				if ($field instanceof FieldTypeDateInterface)
				{
					$this->dates[] = $field->getDBKey();
				}

				$this->setAttribute($field->getDBKey(), $field->getDefaultValue());

				$this->sectionFields[$field->getDBKey()] = $field;
			}

			if (
				isset($this->sectionFields[static::CREATED_AT])
				AND
				isset($this->sectionFields[static::UPDATED_AT])
			)
			{
				$this->timestamps = true;
			}
		}

		parent::__construct($attributes);
	}

	/**
	 * Set a given attribute on the model.
	 *
	 * @param  string $key
	 * @param  mixed $value
	 * @return void
	 */
	public function setAttribute($key, $value)
	{
		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->convertValueToSQL($this, $value);
		}

		parent::setAttribute($key, $value);
	}

	/**
	 * Determine if a get mutator exists for an attribute.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function hasGetMutator($key)
	{
		return isset($this->sectionFields[$key]);
	}

	/**
	 * Get the value of an attribute using its mutator.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return mixed
	 */
	protected function mutateAttribute($key, $value)
	{
		return $this->sectionFields[$key]->onGetDocumentValue($this, $value);
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function getFormValue($key)
	{
		$value = parent::getAttributeValue($key);

		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->onGetFormValue($this, $value);
		}

		return $value;
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string $key
	 * @return mixed
	 */
	public function getHeadlineValue($key)
	{
		$value = parent::getAttributeValue($key);

		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->onGetHeadlineValue($this, $value);
		}

		return $value;
	}

	/**
	 * @return array
	 */
	public function getSectionFields()
	{
		return $this->sectionFields;
	}

	/**
	 * @return array
	 */
	public function getEditableFields()
	{
		$fields = [];
		foreach ($this->getSectionFields() as $key => $field)
		{
			if (!$field->isEditable())
			{
				continue;
			}

			$fields[$key] = $field;
		}

		return $fields;
	}

	/**
	 * @param integer|string $id
	 * @param array|null $fields
	 * @param string|integer|null $primaryKeyField
	 * @return DocumentInterface|null
	 */
	public function getDocumentById($id, array $fields = null, $primaryKeyField = null)
	{
		if (is_null($primaryKeyField))
		{
			$primaryKeyField = $this->primaryKey;
		}

		$query = $this->buildQueryForWidget($fields);

		$result = $query->where($primaryKeyField, $id)->first();

		return is_null($result) ? new static([], $this->section) : $this->newFromBuilder($result);
	}

	/**
	 * @param bool|array|null $fields
	 * @param array $orderRules
	 * @param array $filterRules
	 * @return Collection
	 */
	public function getDocuments($fields = true, array $orderRules = [], array $filterRules = [])
	{
		$items = $this->buildQueryForWidget($fields)->get()->toArray();

		$items = array_map(function ($item) {
			return $this->newFromBuilder($item);
		}, $items);

		return $this->newCollection($items);
	}

	/**
	 * @param bool|array|null $fields
	 * @param array $orderRules
	 * @param array $filterRules
	 * @return Builder
	 */
	protected function buildQueryForWidget($fields = true, array $orderRules = [], array $filterRules = [])
	{
		$query = \DB::table($this->getTable());

		$t = [$this->section->getId() => true];

		$selectFields = [];

		if (is_array($fields))
		{
			foreach ($fields as $fieldId)
			{
				if (!isset($this->sectionFieldsIds[$fieldId]))
				{
					continue;
				}

				$selectFields[] = $this->sectionFieldsIds[$fieldId];
			}
		}
		else if ($fields === true)
		{
			$selectFields = $this->sectionFieldsIds;
		}
		else if ($fields === false)
		{
			$query->selectRaw('COUNT(*) as total_docs');
		}

		// TODO: предусмотреть relation поля
		if ($fields !== false)
		{
			foreach ($selectFields as $field)
			{
				$field->querySelectColumn($query, $this);
			}
		}

		if (!empty($orderRules))
		{
			$this->buildQueryOrdering($query, $orderRules, $t);
		}

		if (!empty($filterRules))
		{
			$this->buildQueryFilters($query, $filterRules, $t);
		}

		return $query;
	}

	/**
	 * @param Builder $query
	 * @param array $orderRules
	 * @param array $t
	 */
	protected function querySelectColumn(Builder $query, array $orderRules, array & $t)
	{
		$j = 0;

		foreach ($orderRules as $rule)
		{
			$field = null;

			$fieldId = key($rule);
			$dir = $rule[key($rule)];

			if (!isset($this->sectionFieldsIds[$fieldId]))
			{
				continue;
			}

			// TODO: предусмотреть relation поля

			$field = $this->sectionFieldsIds[$fieldId];
			$field->queryOrderBy($query, $dir);

			unset($field);

			$j++;
		}
	}

	const COND_EQ       = 0;
	const COND_BTW      = 1;
	const COND_GT       = 2;
	const COND_LT       = 3;
	const COND_GTEQ     = 4;
	const COND_LTEQ     = 5;
	const COND_CONTAINS = 6;
	const COND_LIKE     = 7;
	const COND_NULL     = 8;

	const FILTER_VALUE_PLAIN    = 20;
	const FILTER_VALUE_GET      = 40;
	const FILTER_VALUE_POST     = 50;
	const FILTER_VALUE_BEHAVIOR = 30;

	/**
	 * @param Builder $query
	 * @param array $filterRules
	 * @param array $t
	 */
	protected function buildQueryFilters(Builder $query, array $filterRules, array & $t)
	{
		foreach ($filterRules as $rule)
		{
			$params = [];
			$field = $rule['field'];

			if (!empty($rule['params']))
			{
				parse_str($rule['params'], $params);
			}

			$condition = $rule['condition'];
			$type = $rule['type'];
			$invert = !empty($rule['invert']);

			$value = array_get($rule, 'value');

			if (!is_null($value) and $type != static::FILTER_VALUE_PLAIN)
			{
				switch ($type)
				{
					case self::FILTER_VALUE_BEHAVIOR:
						// TODO: получение значения из behavior

						break;
					case self::FILTER_VALUE_GET:
						$value = \Request::query($value);
						break;
					case self::FILTER_VALUE_POST:
						$value = \Request::input($value);
						break;
					default:
						// TODO: борать значения из хранилища, в которое пользователь сможет добавлять свои данные
						$value = \Request::all($value);
						break;
				}
			}

			if (is_null($value))
			{
				continue;
			}

			$fieldId = $field;

			if (isset($this->sectionFields[$fieldId]))
			{
				$field = $this->sectionFields[$fieldId];
			}
			else if (isset($this->sectionFieldsIds[$fieldId]))
			{
				$field = $this->sectionFields[$fieldId];
			}

			if (!($field instanceof FieldInterface))
			{
				continue;
			}

			// TODO: предусмотреть relation поля
			$inCondition = false;

			switch ($condition)
			{
				case self::COND_EQ:
					$value = explode(',', $value);

					if ($value[0] == '*')
						break;
					elseif (count($value) > 1)
						$inCondition = true;
					else
						$value = $value[0];
					break;

				case self::COND_CONTAINS:
					$value = explode(',', $value);
					$inCondition = true;
					break;
				case self::COND_BTW:
					$value = explode(',', $value, 2);
					if (count($value) != 2) break;
					break;
			}

			$inCondition = $inCondition ? 'IN' : '=';

			$conditions = array($inCondition, 'BETWEEN', '>', '<', '>=', '<=', 'IN', 'LIKE', 'NULL');
			$condition = strtoupper(array_get($conditions, $condition, '='));

			if ($invert)
			{
				switch ($condition)
				{
					case '>':
						$condition = '<=';
						break;
					case '<':
						$condition = '>=';
						break;
					case '=':
						$condition = '!=';
						break;
					case 'IN':
					case 'LIKE':
					case 'NULL':
					case 'BETWEEN':
						$condition = 'NOT ' . $condition;
						break;
					case '>=':
						$condition = '<';
						break;
					case '<=':
						$condition = '>';
						break;
				}
			}

			$field->queryWhereCondition($query, $condition, $value, $params);
		}
	}

	/**
	 * Create a new instance of the given model.
	 *
	 * @param  array  $attributes
	 * @param  bool   $exists
	 * @return static
	 */
	public function newInstance($attributes = [], $exists = false)
	{
		$model = new static($attributes, $this->section);
		$model->exists = $exists;

		return $model;
	}
}