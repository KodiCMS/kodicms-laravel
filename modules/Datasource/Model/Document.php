<?php namespace KodiCMS\Datasource\Model;

use KodiCMS\Datasource\Fields\Primary;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldTypeTimestampInterface;

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
	 * @param SectionInterface $section
	 * @param array $attributes
	 */
	public function __construct(SectionInterface $section, array $attributes = [])
	{
		$this->section = $section;
		$this->table = $this->section->getSectionTableName();

		foreach ($this->section->getFields() as $field)
		{
			if ($field instanceof Primary)
			{
				$this->primaryKey = $field->getDBKey();
				$this->incrementing = true;
			}

			if($field instanceof FieldTypeDateInterface)
			{
				$this->dates[] = $field->getDBKey();
			}

			$this->setAttribute($field->getDBKey(), $field->getDefaultValue());
			$this->sectionFields[$field->getDBKey()] = $field;
		}

		parent::__construct($attributes);

		//TODO: инициализировать timestamps поля
	}

	/**
	 * @return array
	 */
	public function getSectionFields()
	{
		return $this->sectionFields;
	}

	/**
	 * Set a given attribute on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function setAttribute($key, $value)
	{
		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->onSetDocumentAttribute($this, $value);
		}

		parent::setAttribute($key, $value);
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttributeValue($key)
	{
		$value = parent::getAttributeValue($key);

		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->onGetDocumentAttribute($this, $value);
		}

		return $value;
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getFormAttributeValue($key)
	{
		$value = parent::getAttributeValue($key);

		if (!is_null($field = array_get($this->sectionFields, $key)))
		{
			$value = $field->onGetFormAttributeValue($this, $value);
		}

		return $value;
	}
}