<?php namespace KodiCMS\Widgets\Contracts;

interface Widget {

	public function __construct($id, $type, $name, $description = '');

	/**
	 * @return string
	 */
	public function getType();

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return string
	 */
	public function getDescription();

	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @return string
	 */
	public function getSettingsTemplate();

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setSetting($name, $value = null);

	/**
	 * @param array $settings
	 * @return $this
	 */
	public function setSettings(array $settings);

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function getSetting($name, $default = null);

	/**
	 * @return array
	 */
	public function getSettings();

	/**
	 * @param array $parameters
	 * @return $this
	 */
	public function setParameters(array $parameters);

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setParameter($name, $value = null);

	/**
	 * @return array
	 */
	public function getParameters();

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function getParameter($name, $default = null);

	/**
	 * @return array
	 */
	public function getPreparedData();
}