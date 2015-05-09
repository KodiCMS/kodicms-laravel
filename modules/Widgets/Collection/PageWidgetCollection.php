<?php namespace KodiCMS\Widgets\Collection;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class PageWidgetCollection extends WidgetCollection {

	/**
	 * @var FrontendPage
	 */
	protected $page;

	public function __construct(FrontendPage $page)
	{
		$this->page = $page;

		$widgets = WidgetManagerDatabase::getWidgetsByPage($page->getId());
		$blocks = WidgetManagerDatabase::getPageWidgetBlocks($page->getId());

		foreach($widgets as $widget)
		{
			$this->registerWidget($widget, array_get($blocks, $widget->getId()));
		}

		$this->placeWidgetsToLayout();
	}

	/**
	 * @return $this
	 */
	protected function buildWidgetCrumbs()
	{
		foreach ($this->registeredWidgets as $id => $widget)
		{
			$widget = $widget['object'];
			if ($widget->hasBreadcrumbs())
			{
				$widget->changeBreadcrumbs($this->getBreadcrumbs());
			}
		}

		return $this;
	}
}