<?php namespace KodiCMS\Widgets;

use KodiCMS\Widgets\Contracts\ContextInterface;
use KodiCMS\Widgets\Contracts\WidgetInterface;

abstract class WidgetDecorator implements WidgetInterface
{
	/**
	 * @var string
	 */
	protected $frontendTemplate = null;

	/**
	 * @var array
	 */
	protected $frontendParameters = [];


	/**
	 * @var string
	 */
	protected $defaultFrontendTemplate = null;

	/**
	 * @var string
	 */
	protected $settingsTemplate = null;

	/**
	 * @var array
	 */
	protected $settingsParameters = []

	/**
	 * @var ContextInterface
	 */
	protected $context;

	public function __construct(ContextInterface $context)
	{
		$this->context = $context;
	}
}