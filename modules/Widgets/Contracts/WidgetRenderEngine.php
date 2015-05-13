<?php namespace KodiCMS\Widgets\Contracts;

use KodiCMS\Widgets\Contracts\Widget;

interface WidgetRenderEngine
{
	/**
	 * @param Widget $widget
	 */
	public function __construct(Widget $widget, array $parameters = []);

	/**
	 * @return Widget
	 */
	public function getWidget();

	public function render();
}