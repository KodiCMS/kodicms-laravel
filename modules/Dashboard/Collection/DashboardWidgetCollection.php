<?php

namespace KodiCMS\Dashboard\Collection;

use KodiCMS\Dashboard\WidgetManagerDashboard;
use KodiCMS\Widgets\Collection\WidgetCollection;

class DashboardWidgetCollection extends WidgetCollection
{
    /**
     * @param int $userId
     */
    public function __construct($userId = null)
    {
        $widgets = WidgetManagerDashboard::getWidgets();

        foreach ($widgets as $i => $widget) {
            $this->addWidget($widget, $i, array_get($blocks, $widget->getId().'.1'));
        }
    }
}
