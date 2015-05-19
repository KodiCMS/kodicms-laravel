<?php namespace KodiCMS\Datasource\Fields;

use Illuminate\Database\Eloquent\Builder;
use KodiCMS\CMS\Traits\Settings;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Contracts\FieldInterface;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Validation\Validator;

abstract class Decorator implements FieldInterface
{
	use Settings;

	const DB_FIELD_PREFFIX = 'f_';

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * With table preffix
	 *
	 * @var string
	 */
	protected $dbKey;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var bool
	 */
	protected $isRequired = true;

	/**
	 * @var bool
	 */
	protected $isIndexable = false;

	/**
	 * @var bool
	 */
	protected $isSearchable = false;

	/**
	 * @var bool
	 */
	protected $isVisible = true;

	/**
	 * @var string
	 */
	protected $template = null;

	/**
	 * @param string $key
	 * @param array $settings
	 */
	public function __construct($key, array $settings = [])
	{
		$this->setSettings($settings);
		$this->setkey($key);
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
	public function getDBKey()
	{
		return $this->dbKey;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @return bool
	 */
	public function isRequired()
	{
		return $this->isRequired;
	}

	/**
	 * @return bool
	 */
	public function isVisibled()
	{
		return $this->isVisibled;
	}

	/**
	 * @return bool
	 */
	public function isSearchable()
	{
		return $this->isSearchable;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->default;
	}

	/**
	 * @param bool $status
	 */
	public function setVisibleStatus($status)
	{
		$this->isVisible = (bool)$status;
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
	 * Template
	 **************************************************************************/


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
	abstract public function getDatabaseFieldType(Blueprint $table);

	/**
	 * @return array
	 */
	public function toArray()
	{
		return [
			'type' => '',
			'key' => $this->getKey(),
			'dbKey' => $this->getDBKey(),
			'settings' => $this->getSettings(),
			'title' => $this->getTitle(),
			'required' => $this->isRequired(),
			'visible' => $this->isVisible()
		];
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getKey();
	}

	/**
	 * @param string $key
	 */
	protected function setkey($key)
	{
		$this->key = $key;
		$this->dbKey = static::DB_FIELD_PREFFIX . $key;
	}
}