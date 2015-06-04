<?php namespace KodiCMS\Support\Model;

use Form;
use KodiCMS\Support\Traits\Settings;
use KodiCMS\Support\Helpers\Callback;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;

abstract class ModelField implements ModelFieldInterface
{
	use Settings;

	/**
	 * @var int
	 */
	protected static $tabIndex = 100;

	/**
	 * @var string
	 */
	protected $prefix = '';

	/**
	 * @var Model
	 */
	protected $model;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var string
	 */
	protected $modelKey;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @var string|null
	 */
	protected $cast = null;

	/**
	 * @var mixed
	 */
	protected $defaultValue = null;

	/**
	 * @var array
	 */
	protected $settings = [];

	/**
	 * @var ModelFieldAttributes
	 */
	protected $fieldAttributes;

	/**
	 * @var ModelFieldAttributes
	 */
	protected $labelAttributes;

	/**
	 * @param string $key
	 * @param array|null $attributes
	 * @param array|null $settings
	 */
	public function __construct($key, array $attributes = null, array $settings = null)
	{
		$this->key = $key;
		$this->modelKey = $key;
		$this->title = ucwords(str_replace(['_'], ' ', $key));

		if (!is_null($settings))
		{
			$this->setSettings($settings);
		}

		$this->fieldAttributes = new ModelFieldAttributes($attributes);
		$this->labelAttributes = new ModelFieldAttributes;

		$this->boot();
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->getFieldAttributes()->getAttribute('id', $this->model->getTable() . '_' . $this->getKey());
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		if (isset($this->callbackValue))
		{
			return Callback::invoke($this->callbackValue, $this->callbackParameters);
		}

		$value = $this->model->{$this->getModelKey()};

		if($value instanceof Model)
		{
			$value = $value->{$value->getKeyName()};
		}

		if (is_null($value))
		{
			$value = $this->getDefaultValue();
		}

		return $value;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		return $this->defaultValue;
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
	public function getModelKey()
	{
		return $this->modelKey;
	}

	/**
	 * @return int
	 */
	public function getTabIndex()
	{
		return self::$tabIndex++;
	}

	/**
	 * @return string
	 */
	public function getName($prefix = null)
	{
		if (!is_null($prefix))
		{
			$this->setPrefix($prefix);
		}

		return empty($this->prefix) ? $this->getKey() : $this->prefix . '[' . $this->getKey() . ']';
	}

	/**
	 * @return ModelFieldAttributes
	 */
	public function getFieldAttributes()
	{
		return $this->fieldAttributes;
	}

	/**
	 * @return ModelFieldAttributes
	 */
	public function getLabelAttributes()
	{
		return $this->labelAttributes;
	}

	/**
	 * @param string|array $prefix
	 * @return $this
	 */
	public function setPrefix($prefix)
	{
		if (is_array($prefix))
		{
			$firstSegment = array_shift($prefix);

			if (!empty($prefix))
			{
				$prefix = implode('][', $prefix);
				$prefix = $firstSegment . '[' . $prefix . ']';
			}
			else
			{
				$prefix = $firstSegment;
			}
		}

		$this->prefix = $prefix;
		return $this;
	}

	/**
	 * @param Model $model
	 * @return $this
	 */
	public function setModel(Model $model)
	{
		$this->model = $model;
		return $this;
	}

	/**
	 * @param string $key
	 * @return $this
	 */
	public function setModelKey($key)
	{
		$this->modelKey = $key;
		return $this;
	}

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function setDefaultValue($value)
	{
		$this->defaultValue = $value;
		return $this;
	}


	/**
	 * @param string $key
	 * @param array|string $attribute
	 * @return $this
	 */
	public function setAttribute($key, $attribute)
	{
		$this->getFieldAttributes()->setAttribute($key, $attribute);

		return $this;
	}

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function setAttributes(array $attributes)
	{
		$this->getFieldAttributes()->setAttributes($attributes);

		return $this;
	}

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function setLabelAttributes(array $attributes)
	{
		$this->getLabelAttributes()->setAttributes($attributes);

		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->getFieldAttributes()->getAttributes();
	}

	/**
	 * @param array $attributes
	 * @param null|string $title
	 * @return string
	 */
	public function renderFormLabel(array $attributes = [], $title = null)
	{
		$this->getLabelAttributes()->setAttributes($attributes);

		if (is_null($title))
		{
			$title = $this->getTitle();
		}

		return $this->getFormFieldLabel($this->getId(), $title, $this->getLabelAttributes()->getAttributes());
	}

	/**
	 * @param array $attributes
	 * @return string
	 */
	public function renderFormField(array $attributes = [])
	{
		$this->beforeRender();

		$this->getFieldAttributes()->setAttributes($attributes);

		return $this->getFormFieldHTML($this->getName(), $this->getValue(), $this->getAttributes());
	}

	protected function boot()
	{

	}

	protected function beforeRender()
	{

	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @param array $attributes
	 * @return mixed
	 */
	abstract protected function getFormFieldHTML($name, $value, array $attributes);

	/**
	 * @param $id
	 * @param $title
	 * @param array $attributes
	 * @return mixed
	 */
	protected function getFormFieldLabel($id, $title, array $attributes)
	{
		return Form::label($id, $title, $attributes);
	}
}