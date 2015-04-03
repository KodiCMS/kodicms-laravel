<?php namespace KodiCMS\CMS\Breadcrumbs;

use KodiCMS\API\Exceptions\Exception;

/**
 * Class Item
 * @package KodiCMS\CMS\Breadcrumbs
 */
class Item
{
	/**
	 * @var string
	 */
	public $url = NULL;

	/**
	 * @var string
	 */
	public $name = '';

	/**
	 * @var boolean
	 */
	public $active = TRUE;

	/**
	 * @var string
	 */
	protected $data = [];


	/**
	 * @param string $name
	 * @param null|string $url
	 * @param bool $active
	 * @param array $data
	 * @throws Exception
	 */
	public function __construct($name, $url = NULL, $active = FALSE, array $data = [])
	{
		if (empty($name)) {
			throw new Exception('Breadcrumbs: The breadcrumb name could not be empty!');
		}

		$this->name = $name;
		if (!is_null($url)) {
			$this->setUrl($url);
		}

		$this->active = (bool)$active;

		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getLink()
	{
		// TODO: необходимо убрать фильтрацию заголовка
		return \HTML::link($this->getUrl(), $this->getName());
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return (bool) $this->active;
	}

	/**
	 * @param string $url
	 * @return $this
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setActive($status)
	{
		$this->active = (bool) $status;
		return $this;
	}

	/**
	 * @param bool $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @param string $key
	 * @return mixed|NULL
	 */
	public function getAttribute($key)
	{
		return array_get($this->data, $key);
	}

	/**
	 * @param string|array $key
	 * @param mixed $value
	 * @return $this
	 */
	public function setAttribute($key, $value = NULL)
	{
		if (is_array($key)) {
			$this->data = $key;
		} else {
			$this->data[$key] = $value;
		}

		return $this;
	}

	/**
	 * @param string $key
	 * @return midex|NULL
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function __set($key, $value)
	{
		return $this->setAttribute($key, $value);
	}
}