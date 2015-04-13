<?php namespace KodiCMS\Widgets\Traits;

trait Widgetable {

	/**
	 * @var array
	 */
	protected $registeredWidgets = [];

	/**
	 * @var array
	 */
	protected $registeredWidgetsIds = [];

	/**
	 * @var array
	 */
	protected $layoutBlocks = [];

	/**
	 * @param integer $id
	 * @return WidgetDecorator
	 */
	public function getWidgetById($id)
	{
		return array_get($this->registeredWidgets, $id);
	}

	/**
	 * @param $block
	 * @return array
	 */
	public function getWidgetsByBlock($block)
	{
		if(array_key_exists($block, $this->layoutBlocks))
		{
			return $this->layoutBlocks[$block];
		}

		return [];
	}

	/**
	 * @return array
	 */
	public function getLayoutBlocks()
	{
		return array_keys($this->layoutBlocks);
	}

	/**
	 * @param array $widgets
	 * @return $this
	 */
	public function registerWidgets(array $widgets)
	{
		foreach ($widgets as $id => $widget)
		{
			$this->registeredWidgets[$id] = $widget;
		}

		return $this;
	}

	public function injectWidgetsToLayout()
	{
		$this->sortWidgets();

		foreach ($this->registeredWidgetsIds as $id)
		{
			$widget = $this->registeredWidgets[$id];

			if(is_null($widget->getBlock())) continue;

			$this->layoutBlocks[$widget->getBlock()][] = $widget;

			// TODO добавить инициализацию событий
//			if($widget instanceof WidgetDecorator)
//			{
//				$widget->
//			}
		}

	}

	/**
	 * @return $this
	 */
	protected function sortWidgets()
	{
		$ids = array_keys($this->registeredWidgets);

		$widgets = [];
		$types = ['PRE' => [], '*named' => [], 'POST' => []];

		foreach ($ids as $id)
		{
			if (isset($types[$this->registeredWidgets[$id]->getBlock()]))
			{
				$types[$this->registeredWidgets[$id]->getBlock()][] = $id;
			}
			else
			{
				$types['*named'][] = $id;
			}
		}

		foreach ($types as $type => $ids)
		{
			foreach ($ids as $id)
			{
				$widgets[$id] = $this->registeredWidgets[$id];
			}
		}

		$this->registeredWidgetsIds = array_keys($widgets);
		$this->registeredWidgets = $widgets;

		return $this;
	}

	/**
	 * @return $this
	 */
	public function buildWidgetCrumbs()
	{
		foreach ($this->registeredWidgetsIds as $id)
		{
			if (
				($widget = $this->registeredWidgets[$id]) instanceof WidgetDecorator
				AND
				$this->registeredWidgets[$id]->hasBreadcrumbs()
			)
			{
				$widget->changeBreadcrumbs($this->getBreadcrumbs());
			}
		}

		return $this;
	}

}