<?php namespace KodiCMS\Widgets\Engine;

use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Contracts\WidgetRenderEngine;

abstract class WidgetRenderAbstract implements WidgetRenderEngine
{
	/**
	 * @var WidgetRenderable
	 */
	protected $widget;

	/**
	 * @var array
	 */
	protected $parameters;

	/**
	 * @param WidgetRenderable $widget
	 * @param array $parameters
	 */
	public function __construct(WidgetRenderable $widget, array $parameters = [])
	{
		$this->widget = $widget;
		$this->parameters = $parameters;
	}

	/**
	 * @return WidgetRenderable
	 */
	public function getWidget()
	{
		return $this->widget;
	}
}