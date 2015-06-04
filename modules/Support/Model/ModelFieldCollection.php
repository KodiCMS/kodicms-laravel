<?php namespace KodiCMS\Support\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Model\Contracts\ModelFieldCollectionInterface;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;

class ModelFieldCollection implements \Iterator
{
	/**
	 * @var ModelFieldCollectionInterface
	 */
	protected $collection = [];

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @param Model $model
	 * @param array $collection
	 * @throws ModelFieldCollectionException
	 */
	public function __construct(Model $model, $collection = [])
	{
		$this->model = $model;

		if (!($collection instanceof ModelFieldsInterface))
		{
			$collection = $collection->fields();
		}

		if (!is_array($collection))
		{
			throw new ModelFieldCollectionException('Collection must be type [array]');
		}

		foreach ($collection as $field)
		{
			$this->addField($field);
		}
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->collection;
	}

	/**
	 * @param string $name
	 * @return ModelFieldInterface
	 */
	public function getField($name)
	{
		$related = null;

		if (strpos($name, '::') !== false)
		{
			list($name, $related) = explode('::', $name, 2);
		}

		if (is_null($field = array_get($this->collection, $name)))
		{
			return null;
		}

		if (!is_null($related) and method_exists($field, 'getRelatedModel') and (($model = $field->getRelatedModel()) instanceof Model))
		{
			return $model->getField($related);
		}

		return $field;
	}

	/**
	 * @param ModelFieldInterface $field
	 * @return ModelFieldInterface
	 */
	public function addField(ModelFieldInterface $field)
	{
		$field->setModel($this->model);
		return $this->collection[$field->getKey()] = $field;
	}

	/**
	 * @param string $name
	 * @return mixed|null
	 */
	public function getFieldValue($name)
	{
		if (is_null($field = $this->getField($name)))
		{
			return null;
		}

		return $field->getValue();
	}

	/**
	 * @param string $prefix
	 * @return $this
	 */
	public function setFieldPrefix($prefix)
	{
		foreach ($this->collection as $filed)
		{
			$filed->setPrefix($prefix);
		}

		return $this;
	}

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function setFieldAttributes(array $attributes)
	{
		foreach ($this->collection as $filed)
		{
			$filed->setAttributes($attributes);
		}

		return $this;
	}

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function setFieldLabelAttributes(array $attributes)
	{
		foreach ($this->collection as $filed)
		{
			$filed->setLabelAttributes($attributes);
		}

		return $this;
	}

	/************************************
	 * Iterator
	 ************************************/
	public function rewind()
	{
		reset($this->collection);
	}

	public function current()
	{
		return current($this->collection);
	}

	public function key()
	{
		return key($this->collection);
	}

	public function next()
	{
		return next($this->collection);
	}

	public function valid()
	{
		$key = key($this->collection);
		return ($key !== null && $key !== false);
	}
}