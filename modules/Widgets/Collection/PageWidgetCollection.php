<?php namespace KodiCMS\Widgets\Collection;

use Meta;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class PageWidgetCollection extends WidgetCollection {

	/**
	 * @param int $pageId
	 */
	public function __construct($pageId)
	{
		$widgets = WidgetManagerDatabase::getWidgetsByPage($pageId);
		$blocks = WidgetManagerDatabase::getPageWidgetBlocks($pageId);

		foreach($widgets as $widget)
		{
			$this->addWidget($widget, array_get($blocks, $widget->getId() .'.0'), array_get($blocks, $widget->getId() .'.1'));
		}
	}

	/**
	 * @return void
	 */
	public function placeWidgetsToLayoutBlocks()
	{
		foreach ($this->registeredWidgets as $widget)
		{
			if($widget->getObject() instanceof WidgetRenderable)
			{
				Meta::addPackage($widget->getObject()->getMediaPackages());
			}
		}

		parent::placeWidgetsToLayoutBlocks();
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