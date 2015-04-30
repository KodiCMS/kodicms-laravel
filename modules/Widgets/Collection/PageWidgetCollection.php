<?php namespace KodiCMS\Widgets\Collection;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\WidgetManager;

class PageWidgetCollection extends WidgetCollection {

	/**
	 * @var FrontendPage
	 */
	protected $page;

	public function __construct(FrontendPage $page)
	{
		$this->page = $page;

		$widgets = WidgetManager::getWidgetsByPage($page);

		$this->registeredWidgets($widgets);
		$this->placeWidgetsToLayout();
	}
}