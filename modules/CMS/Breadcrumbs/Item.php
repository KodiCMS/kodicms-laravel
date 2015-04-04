<?php namespace KodiCMS\CMS\Breadcrumbs;

use KodiCMS\API\Exceptions\Exception;
use KodiCMS\CMS\Traits\Accessor;

/**
 * Class Item
 * @package KodiCMS\CMS\Breadcrumbs
 */
class Item
{
	use Accessor;

	/**
	 * @var array
	 */
	protected $attributes = [];

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
			$this->url =$url;
		}

		$this->status = $active;

		$this->setAttribute($data);
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
		return '<a href="' . $this->getUrl() . '">' . $this->getName() . '</a>';
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
		return $url;
	}

	/**
	 * @param bool $status
	 * @return $this
	 */
	public function setStatus($status)
	{
		return (bool)$status;
	}

	/**
	 * @param bool $name
	 * @return $this
	 */
	public function setName($name)
	{
		return $name;
	}
}