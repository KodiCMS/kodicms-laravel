<?php namespace KodiCMS\Dashboard\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use Package;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\Dashboard;
use KodiCMS\Dashboard\WidgetRenderDashboardHTML;

class DashboardController extends Controller
{

	public function putWidget()
	{
		$widgetType = $this->getRequiredParameter('widget_type');

		$widget = Dashboard::addWidget($widgetType);

		if (count($widget->media_packages) > 0)
		{
			$this->media = Package::getScripts($widget->media_packages);
		}

		$this->size = $widget->getSize();
		$this->id = $widget->getId();

		$this->setContent((new WidgetRenderDashboardHTML($widget))->render());
	}

	public function deleteWidget()
	{
		$widgetId = $this->getRequiredParameter('id');
		Dashboard::deleteWidgetById($widgetId);
	}

	public function postWidget()
	{
		$widgetId = $this->getRequiredParameter('id');
		$settings = $this->getParameter('settings', []);

		$widget = Dashboard::updateWidget($widgetId, $settings);

		if($widget instanceof WidgetDashboard)
		{
			$this->reloadPage = $widget->isUpdateSettingsPage();
		}
	}
}
