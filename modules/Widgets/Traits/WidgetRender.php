<?php namespace KodiCMS\Widgets\Traits;

use KodiCMS\Widgets\Engine\WidgetRenderHTML;

trait WidgetRender {

	/**
	 * @return string
	 */
	public function getFrontendTemplate()
	{
		return $this->frontendTemplate;
	}

	/**
	 * @return string
	 */
	public function getDefaultFrontendTemplate()
	{
		return $this->defaultFrontendTemplate;
	}

	/**
	 * @return string
	 */
	public function getHeader()
	{
		return $this->header;
	}

	/**
	 * @return array
	 */
	public function getMediaPackages()
	{
		return (array) $this->media_packages;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) new WidgetRenderHTML($this);
	}
}