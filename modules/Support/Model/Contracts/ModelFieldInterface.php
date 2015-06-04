<?php namespace KodiCMS\Support\Model\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ModelFieldInterface {

	/**
	 * @return string
	 */
	public function getId();

	/**
	 * @return mixed
	 */
	public function getValue();

	/**
	 * @return mixed
	 */
	public function getDefaultValue();

	/**
	 * @return string
	 */
	public function getKey();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix);

	/**
	 * @param Model $model
	 */
	public function setModel(Model $model);

	/**
	 * @param string $key
	 * @param string|array $attribute
	 */
	public function setAttribute($key, $attribute);

	/**
	 * @param array $attributes
	 */
	public function setAttributes(array $attributes);

	/**
	 * @return array
	 */
	public function getAttributes();

	/**
	 * @param array $attributes
	 * @return string
	 */
	public function renderFormField(array $attributes = []);

	/**
	 * @param array $attributes
	 * @param null|string $title
	 * @return string
	 */
	public function renderFormLabel(array $attributes = [], $title = null);
}