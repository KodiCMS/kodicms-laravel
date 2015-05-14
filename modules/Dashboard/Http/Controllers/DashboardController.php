<?php namespace KodiCMS\Dashboard\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use Assets;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\Dashboard;
use KodiCMS\Dashboard\WidgetManagerDashboard;

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

	public function getWidgetSettings($id)
	{
		$widget = Dashboard::getWidgetById($id);

		return $this->setContent('partials.settings', compact('widget'));
	}

	public function getWidgetList()
	{
		$widgetSettings = Dashboard::getSettings();
		$types = WidgetManagerDashboard::getAvailableTypes();

		$placedWidgetsTypes = [];
		foreach ($widgetSettings as $widget)
		{
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
