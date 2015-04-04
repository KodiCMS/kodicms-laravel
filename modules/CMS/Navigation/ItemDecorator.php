<?php namespace KodiCMS\CMS\Navigation;

use KodiCMS\CMS\Helpers\UI;
use KodiCMS\CMS\Traits\Accessor;

class ItemDecorator
{
	use Accessor;

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
		$this->setAttribute($data);
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		return (bool)$this->getAttribute('status', FALSE);
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		if (!isset($this->icon)) return NULL;

		return UI::icon($this->icon . ' menu-icon');
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
		return (array)$this->getAttribute('premissions');
	}

	/**
	 * @param boolean $status
	 * @return $this
	 */
	public function setStatus($status = TRUE)
	{
		if ($this->getSection() instanceof Section) {
			$this->getSection()->setStatus((bool)$status);
		}

		return (bool)$status;
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