<?php namespace KodiCMS\Support\Model;

use KodiCMS\Support\Model\Fields\TextField;

trait ModelFieldTrait
{
	/**
	 * @var ModelFieldCollection
	 */
	protected $fieldCollection = null;

	/**
	 * @param string $name
	 * @param array $attributes
	 * @return string
	 */
	public function renderFormField($name, array $attributes = [])
	{
		if (!is_null($field = $this->getField($name)))
		{
			return $field->render($attributes);
		}

		return (new TextField($name))->setModel($this)->render($attributes);
	}

	/**
	 * @param string $name
	 * @param array $attributes
	 * @param string|null $title
	 * @return string
	 */
	public function renderFormLabel($name, array $attributes = [], $title = null)
	{
		if (!is_null($field = $this->getField($name)))
		{
			return $field->getLabel()->render($attributes, $title);
		}
	}

	/**
	 * @param string $name
	 * @return Contracts\ModelFieldInterface
	 */
	public function getField($name)
	{
		return $this->getFieldCollection()->getField($name);
	}

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->getFieldCollection()->getFields();
	}

	/**
	 * @return ModelFieldCollection
	 */
	public function getFieldCollection()
	{
		if (is_null($this->fieldCollection))
		{
			$this->createFormFieldCollection();
		}

		return $this->fieldCollection;
	}

	/**
	 * @return array
	 */
	protected function fieldCollection()
	{
		return [];
	}

	protected function createFormFieldCollection()
	{
		$this->fieldCollection = new ModelFieldCollection($this, $this->fieldCollection());
	}
}