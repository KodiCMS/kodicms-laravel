<?php

namespace KodiCMS\Dashboard\Http\Controllers\API;

use PackageManager;
use KodiCMS\Dashboard\Dashboard;
use KodiCMS\Dashboard\Contracts\WidgetDashboard;
use KodiCMS\Dashboard\WidgetRenderDashboardHTML;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Widgets\Engine\WidgetRenderSettingsHTML;

class DashboardController extends Controller
{
    /**
     * @var array
     */
    protected $permissions = [
        'getWidgetSettings' => 'backend.dashboard.manage',
        'putWidget'         => 'backend.dashboard.manage',
        'postWidget'        => 'backend.dashboard.manage',
        'deleteWidget'      => 'backend.dashboard.manage',
    ];

    public function putWidget()
    {
        $widgetType = $this->getRequiredParameter('widget_type');

        $widget = Dashboard::addWidget($widgetType);

        if (count($widget->media_packages) > 0) {
            $this->media = PackageManager::getScripts($widget->media_packages);
        }

        $this->size = $widget->getSize();
        $this->id = $widget->getId();

        $this->setContent(view('dashboard::partials.temp_block', [
            'widget' => new WidgetRenderDashboardHTML($widget),
        ])->render());
    }

    public function getWidgetSettings()
    {
        $widgetId = $this->getRequiredParameter('id');
        $widget = Dashboard::getWidgetById($widgetId);

        $settingsView = (new WidgetRenderSettingsHTML($widget))->render();
        $this->setContent(
            view('dashboard::partials.settings', compact('widget', 'settingsView'))->render()
        );
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

        if ($widget instanceof WidgetDashboard) {
            $this->updateSettingsPage = $widget->isUpdateSettingsPage();
            $this->widgetId = $widgetId;
            $this->setContent(view('dashboard::partials.temp_block', [
                'widget' => new WidgetRenderDashboardHTML($widget),
            ])->render());
        }
    }
}
