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
	public function getTitle();

	/**
	 * @param sreing|null $prefix
	 * @return string
	 */
	public function getName($prefix = null);

	/**
	 * @param array $attributes
	 * @return $this
	 */
	public function getLabel(array $attributes = null);

	/**
	 * @param string $prefix
	 */
	public function setPrefix($prefix);

	/**
	 * @param Model $model
	 */
	public function setModel(Model $model);

	/**
	 * @param array $attributes
	 * @return string
	 */
	public function render(array $attributes = []);
}