<?php namespace KodiCMS\Widgets\Engine;

use Illuminate\View\View;
use KodiCMS\Widgets\Contracts\WidgetRenderable;

class WidgetRenderSettingsHTML extends WidgetRenderAbstract
{
	public function render()
	{
		$widget = $this->getWidget();

		if (method_exists($widget, 'onSettingsRender'))
		{
			app()->call([$widget, 'onSettingsRender'], $this);
		}

		return $this->getContent();
	}

	/**
	 * @return string
	 */
	protected function getContent()
	{
		$widget = $this->getWidget();
		$widget->setSettings($this->parameters);

		$preparedData = $widget->prepareSettingsData();
		$preparedData['widget'] = $widget;

		return $this->getWidgetTemplate($preparedData);
	}

	/**
	 * @param array $preparedData
	 * @return View
	 */
	protected function getWidgetTemplate(array $preparedData)
	{
		$template = $this->getWidget()->getSettingsTemplate();

		if (empty($template))
		{
			return null;
		}

		return view($template, $preparedData);
	}
}