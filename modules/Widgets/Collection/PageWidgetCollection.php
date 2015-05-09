<?php namespace KodiCMS\Widgets\Collection;

use Meta;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class PageWidgetCollection extends WidgetCollection {

	/**
	 * @var FrontendPage
	 */
	protected $page;

	/**
	 * @param FrontendPage $page
	 */
	public function __construct(FrontendPage $page)
	{
		$this->page = $page;

		$widgets = WidgetManagerDatabase::getWidgetsByPage($page->getId());
		$blocks = WidgetManagerDatabase::getPageWidgetBlocks($page->getId());

		foreach($widgets as $widget)
		{
			$this->addWidget($widget, array_get($blocks, $widget->getId()));

			if($widget instanceof WidgetRenderable)
			{
				Meta::addPackage($widget->getMediaPackages());
			}
		}

		$this->placeWidgetsToLayoutBlocks();
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