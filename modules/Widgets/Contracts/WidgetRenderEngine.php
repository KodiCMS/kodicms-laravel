<?php namespace KodiCMS\Widgets\Contracts;

interface WidgetRenderEngine
{
	/**
	 * @param WidgetRenderable $widget
	 */
	public function __construct(WidgetRenderable $widget, array $parameters = []);

	/**
	 * @return Widget
	 */
	public function getWidget();

	public function render();

	public function __toString();
}