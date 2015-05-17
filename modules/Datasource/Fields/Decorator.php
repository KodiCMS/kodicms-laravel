<?php namespace KodiCMS\Datasource\Fields;

use Illuminate\Database\Eloquent\Builder;
use KodiCMS\CMS\Traits\Settings;
use KodiCMS\Datasource\Contracts\FieldInterface;

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

	public function validationRules()
	{
		return [

		];
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

	public function getDefaultValue()
	{

	}

	/**
	 * @param bool $status
	 */
	public function setVisibleStatus($status)
	{
		$this->isVisible = (bool) $status;
	}

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
	 * EVENTS
	 **************************************************************************/



	/**
	 * @param string $key
	 */
	protected function setkey($key)
	{
		$this->key = $key;
		$this->dbKey = static::DB_FIELD_PREFFIX . $key;
	}
}