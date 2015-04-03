<?php namespace KodiCMS\CMS\Navigation;


class ItemDecorator
{

	/**
	 * @var array
	 */
	protected $attributes = [
		'permissions' => NULL
	];

	/**
	 * @var Section
	 */
	protected $sectionObject;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = [])
	{
		foreach ($data as $key => $value) {
			$this->setAttribute($key, $value);
		}
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed|null
	 */
	public function getAttribute($name, $default = NULL)
	{
		return array_get($this->attributes, $name, $default);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return $this
	 */
	public function setAttribute($name, $value)
	{
		$method = 'set' . ucfirst($name);
		if (method_exists($this, 'set' . ucfirst($name))) {
			$this->attributes[$name] = $this->{$method}();
		} else {
			$this->attributes[$name] = $value;
		}

		return $this;
	}

	/**
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->getAttribute($name);
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		return $this->setAttribute($name, $value);
	}

	/**
	 * @param string $name
	 * @return boolean
	 */
	public function __isset($name)
	{
		return isset($this->attributes[$name]);
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return (bool) $this->getAttribute('status', FALSE);
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getAttribute('name');
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return url($this->getAttribute('url'));
	}

	/**
	 * @return array
	 */
	public function getPermissions()
	{
		return (array) $this->getAttribute('premissions');
	}

	/**
	 * @param boolean $status
	 * @return $this
	 */
	public function setStatus($status = TRUE)
	{
		if ($this->getSection() instanceof Section) {
			$this->getSection()->setStatus((bool) $status);
		}

		return $this->attributes['status'] = (bool) $status;
	}

	/**
	 * @param Section $section
	 * @return $this
	 */
	public function setSection(Section & $section)
	{
		$this->sectionObject = $section;
		return $this;
	}

	/**
	 *
	 * @return Section
	 */
	public function getSection()
	{
		return $this->sectionObject;
	}
}