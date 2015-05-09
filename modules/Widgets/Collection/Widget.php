<?php namespace KodiCMS\Widgets\Collection;

use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;

class Widget {

	/**
	 * @var WidgetInterface
	 */
	protected $widget;

	/**
	 * @var string
	 */
	protected $block;

	/**
	 * @param WidgetInterface $widget
	 * @param string $block
	 */
	public function __construct(WidgetInterface $widget, $block)
	{
		$this->widget = $widget;
		$this->block = $block;
	}

	/**
	 * @return WidgetInterface
	 */
	public function getObject()
	{
		return $this->widget;
	}

	/**
	 * @return string
	 */
	public function getBlock()
	{
		return $this->block;
	}
}