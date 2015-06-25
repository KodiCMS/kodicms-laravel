<?php namespace KodiCMS\Dashboard\Http\Controllers;

use Assets;
use KodiCMS\Dashboard\Dashboard;
use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DashboardController extends BackendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'dashboard::';

	public function getIndex()
	{
		Assets::package(['gridster']);

		$widgets = WidgetManagerDashboard::getWidgets();

		$this->setContent('dashboard', compact('widgets'));
	}

	public function getWidgetList()
	{
		$widgetSettings = Dashboard::getSettings();
		$types = WidgetManagerDashboard::getAvailableTypes();

		$placedWidgetsTypes = [];
		foreach ($widgetSettings as $widget)
		{
			$widget = WidgetManagerDashboard::toWidget($widget);

			if ($widget instanceof WidgetDashboard)
			{
				$placedWidgetsTypes[$widget->getType()] = $widget->isMultiple();
			}
		}

		foreach ($types as $type => $data)
		{
			if (array_get($placedWidgetsTypes, $type) === false)
			{
				unset($types[$type]);
			}
		}

		return $this->setContent('partials.widgets', compact('types'));
	}
}
