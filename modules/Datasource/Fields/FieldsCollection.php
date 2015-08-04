<?php namespace KodiCMS\Datasource\Fields;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\FieldsCollectionInterface;

class FieldsCollection implements Arrayable, FieldsCollectionInterface, \Countable, \ArrayAccess, \IteratorAggregate
{
	/**
	 * @var array
	 */
	protected $fields = [];

	/**
	 * @var array
	 */
	protected $fieldIds = [];

	/**
	 * @var array
	 */
	protected $fieldNames = [];

	/**
	 * @param Collection|array $fields
	 */
	public function __construct($fields)
	{
		foreach ($fields as $field)
		{
			$this->fields[$field->getDBKey()] = $field;
			$this->fieldIds[$field->getId()] = $field;
			$this->fieldNames[$field->getDBKey()] = $field->getName();
		}
	}

	/**
	 * @param integer $id
	 *
	 * @return FieldInterface|null
	 */
	public function getById($id)
	{
		return array_get($this->fieldIds, $id);
	}

	/**
	 * @param string $key
	 *
	 * @return FieldInterface|null
	 */
	public function getByKey($key)
	{
		return array_get($this->fields, $key);
	}

	/**
	 * @return array
	 */
	public function getIds()
	{
		return array_keys($this->fieldIds);
	}

	/**
	 * @return array
	 */
	public function getKeys()
	{
		return array_keys($this->getFields());
	}

	/**
	 * @return array
	 */
	public function getNames()
	{
		return $this->fieldNames;
	}

	/**
	 * @return Collection
	 */
	public function getFields()
	{
		return $this->fields;
	}

	/**
	 * @param array| string $keys
	 *
	 * @return array
	 */
	public function getOnly($keys)
	{
		if (!is_array($keys))
		{
			$keys = func_get_args();
		}

		return array_only($this->fields, $keys);
	}

	/**
	 * @return array
	 */
	public function getEditable()
	{
		return new static(array_filter($this->getFields(), function ($field)
		{
			return $field->isEditable();
		}));
	}

	/**
	 * Get the instance as an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->getFields();
	}

	/**
	 * @return  integer
	 */
	public function count()
	{
		return count($this->getFields());
	}


	public function rewind()
	{
		reset($this->fields);
	}


	public function current()
	{
		return current($this->fields);
	}

	/**
	 * @return integer
	 */
	public function key()
	{
		return key($this->fields);
	}

	/**
	 * @return Item
	 */
	public function next()
	{
		return next($this->fields);
	}

	/**
	 * @return boolean
	 */
	public function valid()
	{
		return (!is_null($key = key($this->fields)) AND $key !== false);
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->fields[$key]);
	}

	/**
	 * @param string $key
	 *
	 * @return FieldInterface|null
	 */
	public function __get($key)
	{
		return $this->getByKey($key);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 *
	 * @param mixed $key <p>
	 * An offset to check for.
	 * </p>
	 *
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($key)
	{
		return isset($this->fields[$key]);
	}

	/**
	 * @param string $key
	 * @return FieldInterface|null
	 */
	public function offsetGet($key)
	{
		return $this->getByKey($key);
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function offsetSet($key, $value)
	{
		// TODO: Implement offsetSet() method.
	}

	/**
	 * @param string $key
	 * @return void
	 */
	public function offsetUnset($key)
	{
		// TODO: Implement offsetUnset() method.
	}

	/**
	 * @return \ArrayIterator
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->fields);
	}
}