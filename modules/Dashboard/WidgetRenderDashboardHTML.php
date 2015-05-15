<?php namespace KodiCMS\Dashboard;

use Illuminate\View\View;
use KodiCMS\Widgets\Engine\WidgetRenderHTML;

class WidgetRenderDashboardHTML extends WidgetRenderHTML
{
	/**
	 * @param array $preparedData
	 * @return View
	 */
	protected function getWidgetTemplate(array $preparedData)
	{
		$widget = $this->getWidget();

		$preparedData['widget'] = $widget;
		$template = $widget->getFrontendTemplate();
		return view($template, $preparedData);
	}
}